window.subscribe = function(id, loggedIn){
    return {
        immoLiveId: id,
        isLoggedin: loggedIn,
        showSubscriptionForm: false,
        confirm: false,
        email: true,
        question: '',
        submit: function (){
            this.$refs.subsribe.submit();
        }
    }
}

window.counter = function (datetime) {
    return {
        end: datetime,
        counts: '',
        days: '',
        hours: '',
        minutes: '',
        seconds: '',
        count() {


            setInterval(() => {
                var end = moment(this.end, 'DD.MM.YYYY HH:mm:ss');
                const zeroPad = (num, places) => String(num).padStart(places, '0')
                var diff = moment().diff(moment(end), 'seconds') * -1;
                this.days = zeroPad(parseInt(diff / (60 * 60 * 24)), 2);
                this.hours = zeroPad(parseInt((diff - (this.days * 60 * 60 * 24)) / (60 * 60)),2);
                this.minutes = zeroPad(parseInt((diff - ((this.days * 60 * 60 * 24) + (this.hours * 60 * 60))) / 60),2);
                this.seconds = zeroPad(parseInt((diff - ((this.days * 60 * 60 * 24) + (this.hours * 60 * 60) + (this.minutes * 60)))),2);
            }, 1000)
        }
    }
}