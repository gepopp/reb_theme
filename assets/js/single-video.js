
import Vimeo from "@vimeo/player";

window.prerolled = function (main_id, preroll, skip) {
    return {
        mainId: main_id,
        preroll: preroll,
        isPreroll: true,
        isPlaying: true,
        isLoaded: true,
        countdown: skip,
        mainPlayer: false,
        prerollPlayer: false,
        init() {
            var main = {
                id: parseInt(this.mainId),
                width: "1280",
                responsive: true,
                controls: true,
                autoplay: false
            };

            this.mainPlayer = new Vimeo('mainplayer', main);

            this.mainPlayer.on('loaded', () => {
                this.isLoaded = true
            });

            this.mainPlayer.on('loaded', (e) => {
                this.mainPlayer.getDuration().then((d) => {

                        if (this.preroll.preroll_id && d > 0) {
                        this.prerollPlayer = new Vimeo('prerollplayer', {
                            id: this.preroll.preroll_id,
                            width: "1280",
                            responsive: true,
                            controls: true,
                            autoplay: true,
                        });
                        this.prerollPlayer.on('play', () => {
                            this.isLoaded = true;
                            this.countPreroll();
                        });
                        this.prerollPlayer.on('playing', () => this.isPlaying = true);
                        this.prerollPlayer.on('loaded', (e) => {
                            this.isLoaded = true;
                        });
                    }else{
                        this.isPreroll = false;
                        this.isPlaying = true;
                        this.mainPlayer.on('playing', () => this.isPlaying = true);
                    }
                });
            });
        },
        countPreroll() {
            var timer = window.setInterval(() => {
                if (this.countdown > 0) {
                    this.countdown--;
                } else {
                    clearInterval(timer);
                }
            }, 1000);

            this.prerollPlayer.on('ended', () => {
                this.playMain();
            });
        },
        playMain(autoplay = true) {
            this.isPreroll = false;
            this.prerollPlayer.destroy();
            this.mainPlayer.play();
        },
        play() {
            this.isPlaying = true;
            if(this.prerollPlayer !== false){
                this.prerollPlayer.play();
            }else{
                this.mainPlayer.play();
            }
        }
    }
}