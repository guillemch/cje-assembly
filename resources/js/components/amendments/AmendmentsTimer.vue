<template>
    <span>
        {{ time }}
    </span>
</template>

<script>
    export default {
        name: 'amendments-timer',

        props: {
            opened: String
        },

        data () {
            return {
                startTime: Date.now(),
                currentTime: Date.now(),
                interval: null
            }
        },

        mounted () {
            this.startTime = new Date(this.opened);
            this.interval = setInterval(this.updateCurrentTime, 1000);
        },

        destroyed: function() {
            clearInterval(this.interval)
        },

        sockets: {
            refresh_vote: function (data) {
                this.reset();
            }
        },

        computed: {
            time: function() {
                return this.minutes + ':' + this.seconds;
            },
            milliseconds: function() {
                return this.currentTime - this.startTime;
            },
            minutes: function() {
                const lapsed = this.milliseconds;
                const min = Math.floor((lapsed / 1000 / 60) % 60);
                return min >= 10 ? min : '0' + min;
            },
            seconds: function() {
                const lapsed = this.milliseconds;
                const sec = Math.ceil((lapsed / 1000) % 60);
                return sec >= 10 ? sec : '0' + sec;
            }
        },

        methods: {
            updateCurrentTime () {
                this.currentTime = Date.now();
            },

            reset () {
                this.startTime = Date.now();
                this.currentTime = Date.now();
            }
        }
    }
</script>
