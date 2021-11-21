window.participateButton = () => {
    return {
        buttontext : window.messages.participate,
        is_loading : false,
        error : '',
        stream_id : false,
        participate(stream_id){

            this.stream_id = stream_id;
            this.is_loading = true;
            this.buttontext = window.messages.participate_saving;

            var params = new URLSearchParams();
            params.append('action', 'immolive_subscription');
            params.append('nonce', window.messages.nonce);
            params.append('id', this.stream_id);

            axios.post(window.ajaxurl, params)
                .then((msg) => {
                    setTimeout(() => {

                        this.is_loading = true;
                        this.buttontext = window.messages.participate_sending;

                        var params = new URLSearchParams();
                        params.append('action', 'immolive_subscription_email');
                        params.append('nonce', window.messages.nonce);
                        params.append('id', this.stream_id);

                        axios.post(window.ajaxurl, params)
                            .then((msg) => {
                                this.is_loading = false;
                                this.buttontext = window.messages.participate_completed;
                                setTimeout(() => location.reload(), 1000)
                            })
                            .catch((err) => {
                                this.is_loading = false;
                                this.buttontext = window.messages.participate;
                                this.error      = window.messages.participate_sending_error;
                            });
                    }, 1500);
                })
                .catch((err) => {
                    console.log(err);
                    this.is_loading = false;
                    this.buttontext = window.messages.participate;
                    this.error      = window.messages.participate_saving_error;
                });
        },
    }
}