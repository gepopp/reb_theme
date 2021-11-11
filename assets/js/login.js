window.loginForm = function (formdata) {
    return {
        email: formdata.email !== undefined ? formdata.email : '',
        password: '',
        remember: false,
        completed: false,
        error: {
            email: false,
            password: false,
        },
        ValidateEmail() {
            if (/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(this.email)) {
                var params = new URLSearchParams();
                params.append('action', 'user_exists');
                params.append('email', this.email);

                axios.post(window.ajaxurl, params)
                    .then((rsp) => {
                        this.error.email = false;
                        this.checkCompleted();
                    })
                    .catch((err) => {
                        this.error.email = "Diese E-Mail Adresse ist nicht registriert.";
                        this.checkCompleted();
                    });
            } else {
                this.error.email = "Bitte eine gültige E-Mail Adresse eingeben.";
                this.checkCompleted();
            }
        },
        checkCompleted() {

            if (this.email == '' || this.password == '' || this.error.email != false) {
                this.completed = false;
            } else {
                this.completed = true;
            }

        },
        resendConfirmation() {

            if (this.email != '') {

                var params = new URLSearchParams();
                params.append('action', 'resend_confirmation_email');
                params.append('email', this.email);

                axios.post(window.ajaxurl, params)
                    .then((rsp) => {
                        this.error.global = false;
                        this.successMessage = "Wir haben Ihnen ein E-Mail gesendet, bitte überprüfen Sie Ihren Posteingang.";
                    })
                    .catch((err) => {
                        this.error.global = err.data;
                    });
            }
        }
    }
}
window.registerForm = (formdata) => {
    return {
        data: {
            gender: formdata.register_gender !== undefined ? formdata.register_gender : '',
            firstname: formdata.first_name !== undefined ? formdata.first_name : '',
            lastname: formdata.last_name !== undefined ? formdata.last_name : '',
            email: formdata.register_email !== undefined ? formdata.register_email : '',
            password: '',
            token: false,
        },
        regsiter_errors: {
            gender: false,
            firstname: false,
            lastname: false,
            email: false,
            password: false
        },
        validate(e) {

            e.preventDefault();

            this.resetErrors();

            if (this.data.gender == '') {
                this.regsiter_errors.gender = messages.select;
            }

            if (this.data.lastname == '') {
                this.regsiter_errors.lastname = messages.enter_last_name;
            }

            this.ValidateEmail();

            if (this.data.password.length < 8) {
                this.regsiter_errors.password = messages.password_min;
            }


        },
        valid() {
            for (var o in this.regsiter_errors)
                if ( this.regsiter_errors[o] !== false ) return false;
            var self = this;

            grecaptcha.ready(function() {
                grecaptcha.execute('6Ldhsu4aAAAAAGj0UZRfizcHjtqKqPrPrxF_hsE0', {action: 'submit'}).then(function(token) {
                    self.data.token = token;

                    window.setTimeout(()=>{
                        self.$refs.form.submit();
                    }, 1500)

                });
            });
        },
        resetErrors() {
            this.regsiter_errors = {
                gender: false,
                firstname: false,
                lastname: false,
                email: false,
                password: false
            }
        },
        ValidateEmail() {
            this.regsiter_errors.email = messages.email_proofing;
            if (/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(this.data.email)) {
                var params = new URLSearchParams();
                params.append('action', 'user_exists');
                params.append('email', this.data.email);

                axios.post(window.ajaxurl, params)
                    .then((rsp) => {
                        this.regsiter_errors.email = messages.email_exists;
                    })
                    .catch((err) => {
                        this.regsiter_errors.email = false;
                        this.valid();
                    });
            } else {
                this.regsiter_errors.email = messages.email_invalid;
            }
        },
    }
}