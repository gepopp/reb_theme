window.readingFunctions = function (user_id) {
    return {
        user_id: user_id,
        showHint: false,
        bookmarkSet: false,
        loginRequired: false,
        reminderSet: false,
        load() {
            var current = cookie.get('reading-hint');
            if (current == undefined) {
                window.setTimeout(() => {
                    this.showHint = true;
                }, 5000);
            }
        },
        close(withCookie) {
            if (withCookie) {
                cookie.set('reading-hint', true, {expires: 180});
            }
            this.showHint = false;
        },
        setBookmark(id) {

            if (this.user_id == 0) {
                this.loginRequired = true;
                return;
            }

            var params = new URLSearchParams();
            params.append('action', 'set_user_bookmark');
            params.append('id', id);

            axios.post(window.ajaxurl, params)
                .then((rsp) => {
                    this.bookmarkSet = true;
                })
                .catch((err) => {
                    this.bookmarkSet = true;
                });
        },
        remindReading(id) {

            if (this.user_id == 0) {
                this.loginRequired = true;
                return;
            }

            var params = new URLSearchParams();
            params.append('action', 'set_reading_reminder');
            params.append('id', id);

            axios.post(window.ajaxurl, params, {
                //AxiosRequestConfig parameter
                withCredentials: true //correct
            })
                .then((rsp) => {
                    this.reminderSet = true;
                })
                .catch((err) => {
                    console.log(err.response);
                    this.reminderSet = true;
                });
        }
    }
}

window.readTime = function (text) {
    return {
        text: text,

        get minutes() {
            var reading = readingTime(this.text);
            var seconds = reading.time / 1000;
            var minutes = parseInt(seconds / 60);
            if (minutes > 1) {
                return minutes + ' Minuten';
            } else {
                return '1 Minute';
            }
        }

    }
}

window.articleViews = function (id) {
    return {
        views: false,
        viewsXHR() {

            let views = 0;

            var params = new URLSearchParams();
            params.append('action', 'get_page_views_from_ga_api');
            params.append('id', id);

            axios.post(window.ajaxurl, params)
                .then((rsp) => {
                    this.views = rsp.data;
                });

        }
    }
}

window.readingLog = function (user, post) {
    return {
        user: user,
        post: post,
        depth: 0,
        maxdepth: 0,
        winheight: 0,
        docheight: 0,
        trackLength: 0,
        throttlescroll: 0,
        log() {
            if (this.user != 0) {

                if (this.depth > this.maxdepth) {

                    this.maxdepth = this.depth;

                    var params = new URLSearchParams();
                    params.append('action', 'update_reading_log');
                    params.append('post', this.post);
                    params.append('user', this.user);
                    params.append('depth', this.depth);


                    axios.post(window.ajaxurl, params)
                        .then((rsp) => {
                            this.maxdepth = rsp.data
                        })
                        .catch(()=>{});

                }
            }
        },
        getDocHeight() {
            var D = document;
            return Math.max(
                D.body.scrollHeight, D.documentElement.scrollHeight,
                D.body.offsetHeight, D.documentElement.offsetHeight,
                D.body.clientHeight, D.documentElement.clientHeight
            )
        },
        getmeasurements() {

            this.winheight = window.innerHeight || (document.documentElement || document.body).clientHeight
            this.docheight = this.getDocHeight()
            this.trackLength = this.docheight - this.winheight
        },
        amountscrolled() {

            var scrollTop = window.pageYOffset || (document.documentElement || document.body.parentNode || document.body).scrollTop
            var pctScrolled = Math.floor(scrollTop / this.trackLength * 100) // gets percentage scrolled (ie: 80 or NaN if tracklength == 0)

            this.depth = pctScrolled + 20;

           this.log();
        }
    }
}
