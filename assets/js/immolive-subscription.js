window.immolivesubscription = function (id, name = '', email = '') {

    return {
        load: false,
        name: name,
        email: email,
        question: '',
        errors: {
            name: false,
            email: false,
            question: false
        },
        error: false,
        success: false,
        init(){
            this.load = true;
            var params = new URLSearchParams();
            params.append('action', 'immolive_is_subscribed');
            params.append('nonce', window.messages.nonce);
            params.append('id', id);

            axios.post(window.ajaxurl, params)
                .then((msg) => {
                    this.load = false;
                    if( msg.data ){
                        this.success = "Sie sind bereits angemeldet."
                    }
                });

        },
        validate() {
            if (this.email != email) {
                this.errors.email = "Sie können sich nur mit der E-Mail Adresse mit der sie eingeloggt sind anmelden.";
                this.email = email;
                return;
            } else {
                this.errors.email = false;
            }

            if (this.name != name) {
                this.errors.name = "Sie können sich nur mit dem Namen mit der sie eingeloggt sind anmelden.";
                this.name = name;
                return;
            } else {
                this.errors.name = false;
            }

            this.load = true;

            var params = new URLSearchParams();
            params.append('action', 'immolive_subscription');

            params.append('nonce', window.messages.nonce);
            params.append('id', id);
            params.append('name', this.name);
            params.append('email', this.email);
            params.append('question', this.question);

            axios.post(window.ajaxurl, params)
                .then((msg) => {
                    this.load = false;
                    this.success = msg.data;
                })
                .catch((err) => {
                    this.load = false;
                    this.error = err.response.data;
                });
        }
    }
}