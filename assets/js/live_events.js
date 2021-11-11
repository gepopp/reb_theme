
const Api = axios.create({
    baseURL: messages.rootapiurl,
    headers: {
        'content-type': 'application/json',
        'X-WP-Nonce': messages.nonce
    }
});


window.addComment = function (user, post) {
    return {
        showAll: false,
        isLoading: true,
        comment: '',
        commentError: false,
        addAnswer: false,
        answer: '',
        comments: [],
        children: [],
        user: user,
        post: post,
        maxHeight: 'none',
        openAnswer(comment) {

            for (var i = 1; i <= comment.child_count; i++) {
                this.children.push({
                    id: false,
                    author_avatar_urls: {48: ''},
                    date: '',
                    content: {
                        rendered: ''
                    }
                });
            }

            this.addAnswer = comment.id;
            Api.get('/wp/v2/comments?order=asc&post=' + this.post + '&parent=' + comment.id).then((rsp) => this.children = rsp.data);


        },
        validate(parent = null) {

            this.commentError = false;

            if (this.comment == '' && this.answer == '') return;


            Api.post('/wp/v2/comments', {
                author: this.user,
                content: !this.addAnswer ? this.comment : this.answer,
                post: this.post,
                parent: parent,
            }).then((response) => {
                console.log('hier');
                this.comment = '';
                this.answer = '';
                this.loadComments();
                if (parent !== null) {
                    Api.get('/wp/v2/comments?order=asc&post=' + this.post + '&parent=' + parent).then((rsp) => this.children = rsp.data);
                }
            }).catch((err) => {
                this.commentError = err.response.data.message;
            });
        },
        init() {

            var video = document.getElementById('video-holder');
            if(video !== null){
                this.maxHeight = video.offsetHeight + 'px';
            }
            window.addEventListener('resize', () => {
                var video = document.getElementById('video-holder');
                if(video !== null){
                    this.maxHeight = video.offsetHeight + 'px';
                }
            });


            this.loadComments();
            setInterval(() => {
                this.loadComments();
            }, 5000)
        },
        loadComments() {
            Api.get('/wp/v2/comments?post=' + this.post + '&parent=0')
                .then((rsp) => this.comments = rsp.data)
                .catch()
                .then(() => this.isLoading = false);
        },
        formatDate(date) {
            return new moment(date).locale('de').fromNow();
        }
    }
}