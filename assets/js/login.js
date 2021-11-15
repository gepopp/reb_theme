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
                        this.error.email = window.messages.email_not_registered;
                        this.checkCompleted();
                    });
            } else {
                this.error.email = window.messages.email_invalid;
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
                        this.successMessage = window.messages.email_sent;
                    })
                    .catch((err) => {
                        this.error.global = err.data;
                    });
            }
        }
    }
}
window.registerForm = (formdata, captchaKey) => {
    return {
        data: {
            gender: formdata.register_gender !== undefined ? formdata.register_gender : '',
            firstname: formdata.first_name !== undefined ? formdata.first_name : '',
            lastname: formdata.last_name !== undefined ? formdata.last_name : '',
            email: formdata.register_email !== undefined ? formdata.register_email : '',
            password: '',
            token: false,
            agb : false
        },
        regsiter_errors: {
            gender: false,
            firstname: false,
            lastname: false,
            email: false,
            password: false
        },
        is_loading: false,
        validate(e) {

            this.is_loading = true;


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
                if ( this.regsiter_errors[o] !== false ){
                    this.is_loading = false;
                    return false;
                }
            var self = this;

            grecaptcha.ready(function() {
                grecaptcha.execute(captchaKey, {action: 'submit'}).then(function(token) {
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
            if (/^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/.test(this.data.email)) {
                var params = new URLSearchParams();
                params.append('action', 'user_exists');
                params.append('email', this.data.email);

                axios.post(window.ajaxurl, params)
                    .then((rsp) => {
                        this.is_loading = false;
                        this.regsiter_errors.email = messages.email_exists;
                    })
                    .catch((err) => {
                        this.valid();
                    });
            } else {
                this.regsiter_errors.email = messages.email_invalid;
                this.is_loading = false;
            }
        },
    }
}