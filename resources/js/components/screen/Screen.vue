<template>
    <fullscreen ref="fullscreen" @change="fullscreenChange" class="fullscreen">
        <div class="screen">
            <transition name="fade">
                <div v-if="screen.vote !== null" class="vote-info">
                    <div class="vote-name">
                        <h1>{{ screen.vote.name }}</h1>
                    </div>
                    <transition name="fade">
                    <div class="vote-results row" v-show="screen.just_closed">
                        <div class="col-7">
                            <screen-results :amendment="screen.vote" />
                        </div>
                        <div class="col-5">
                            <screen-results-by-group :results="screen.vote.results.by_group" :compensate="screen.vote.results.compensate" />
                        </div>
                    </div>
                    </transition>
                    <transition name="fade">
                        <div :class="{ 'screen-password': true, 'next-alert': screen.next_alert }" v-if="screen.vote !== null && !screen.just_closed">
                            <screen-code :code="screen.code" />
                        </div>
                        <div v-else-if="screen.just_closed" class="just-closed-bar">
                            <i class="far fa-lock-alt" /> Cerrada
                        </div>
                    </transition>
                </div>
            </transition>
            <transition name="fade">
                <div :class="{ 'countdown': true, 'ongoing-vote': screen.vote !== null }" v-if="countdown.time !== null">
                    <h1 v-if="countdown.speaker">{{ countdown.speaker.name }}</h1>
                    <h3 v-if="countdown.speaker">{{ countdown.speaker.group.name }}</h3>
                    <div class="text-center">
                        <countdown :time="countdown.time" :class="{ 'countdown-timer': true, 'countdown-alert': countdown.alert }" @end="resetCountdown" @progress="handleCountdown">
                            <template slot-scope="props">{{ props.minutes | time }}:{{ props.seconds | time }}</template>
                        </countdown>
                    </div>
                </div>
            </transition>
            <div :class="{ 'screen-logo': true, 'vote-active': (screen.vote !== null || countdown.time !== null) }">
                <div class="logo">
                    <img src="../../../images/logo.jpg" alt="Logo" />
                    <button type="button" @click="toggleFullscreen" v-if="!fullscreen" class="fullscreen-button">Fullscreen</button>
                </div>
            </div>
        </div>
    </fullscreen>
</template>

<script>
    import ScreenResults from './ScreenResults';
    import ScreenResultsByGroup from './ScreenResultsByGroup';
    import ScreenCode from './ScreenCode';
    import VueCountdown from '@chenfengyuan/vue-countdown';

    export default {
        name: 'screen',

        components: {
            ScreenResults,
            ScreenResultsByGroup,
            ScreenCode,
            'countdown': VueCountdown
        },

        data () {
            return {
                screen: { vote: null, code: null, just_closed: false, next_alert: false },
                countdown: { time: null, speaker: null, alert: false },
                loading: false,
                connected: false,
                interval: null,
                ticking: false,
                fullscreen: false
            };
        },

        mounted () {
            this.getScreen(true);
        },

        sockets: {
            connect: function () {
                this.connected = true;
            },
            refresh_vote: function (data) {
                this.getScreen(true);
            },
            refresh_speaker: function (payload) {
                this.countdown.time = payload.time;
                if (payload.speaker) {
                    this.countdown.speaker = payload.speaker;
                } else {
                    this.countdown.speaker = null;
                }
            },
            disconnect: function () {
                this.connected = false;
            }
        },

        methods: {
            getScreen (allowRefresh) {
                this.loading = true;
                console.log('Getting vote...');
                API.getScreen().then(screen => {
                    this.screen = screen;
                    this.loading = false;

                    if (this.screen.vote && !this.screen.just_closed) {
                        console.log('Has vote');
                        if (allowRefresh && !this.ticking) {
                            console.log('Set refresh');
                            this.ticking = true;
                            this.interval = setInterval(() => { this.getScreen(false); }, 1000);
                        }
                    } else {
                        console.log('Resetting interval');
                        clearInterval(this.interval);
                        this.interval = null;
                        this.ticking = false;
                        if (this.screen.just_closed) {
                            setTimeout(() => { this.getScreen(false); }, 35000);
                        }
                    }
                });
            },

            toggleFullscreen () {
                this.$refs.fullscreen.toggle();
            },

            fullscreenChange (fullscreen) {
                this.fullscreen = fullscreen
            },

            handleCountdown(data) {
                if(data.totalSeconds < 10) {
                    this.countdown.alert = true;
                } else {
                    this.countdown.alert = false;
                }
            },

            resetCountdown () {
                setTimeout(() => {
                    this.countdown = { time: null, speaker: null };
                }, 5000);
            }
        },

        filters: {
            time: function (value) {
                const time = String(value);
                return (time.length === 1) ? '0' + time : time;
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import '../../../sass/variables';
    @import '~bootstrap/scss/functions';
    @import '~bootstrap/scss/variables';
    @import '~bootstrap/scss/mixins';

    .fullscreen {
        background: $body-bg !important;
    }

    .screen {
        &-logo {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            width: 100%;
            height: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            background: $cje-yellow;
            background-image: url(../../../images/background.jpg);
            background-size: cover;
            background-position: center;
            z-index: 1000;
            transition: height 1s ease-in-out;

            &.vote-active {
                height: 10vh;

                .logo img {
                    border: .25vw $white solid;
                    width: 7vw;
                }
            }

            .logo img {
                border: 1vw $white solid;
                width: 30vw;
                transition: 1s ease-in-out;
            }
        }

        .vote-info,
        .countdown {
            position: fixed;
            z-index: 10;
            top: 10vh;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 4vw 8vw;
        }

        .screen-password,
        .just-closed-bar {
            position: fixed;
            z-index: 100;
            bottom: 0;
            left: 0;
            right: 0;
            background: $blue;
            font-size: 4vw;
            padding: 1.5vw;
            color: $white;
            text-align: center;

            &.next-alert {
                animation: alert 1s infinite;
            }
        }

        .just-closed-bar {
            background: $red;
        }

        .vote-name {
            h1 {
                font-size: 4vw;
                font-weight: bold;
                padding-right: 18vw;
            }

            .just-closed {
                background: $red;
                font-weight: bold;
                border-radius: .25rem;
                padding: .5vw 1vw;
                color: $white;
                font-size: 1.75vw;
            }
        }

        .countdown {
            h1 {
                font-size: 5.5vw;
                font-weight: bold;
            }

            h3 {
                font-size: 3.5vw;
                color: $gray-600;
            }

            &-timer {
                display: inline-block;
                font-size: 12vw;
                border: .75vw $gray-300 solid;
                padding: 0 3vw;
                width: 56vw;
                border-radius: 2rem;
                margin-top: 10vw;
            }

            &-alert {
                animation: alert-countdown 1s infinite;
            }

            &.ongoing-vote {
                display: flex;
                justify-content: flex-end;
                align-items: flex-start;

                h1 {
                    font-size: 2vw;
                    padding: 1.5vw;
                    background: $gray-100;
                }

                h3 {
                    display: none;
                }

                .countdown-timer {
                    font-size: 3vw;
                    width: 15vw;
                    margin-top: 0;
                    float: right;
                    border-width: .25vw;
                }
            }
        }
    }

    .fullscreen-button {
        position: fixed;
        top: 2rem;
        right: 2rem;
        z-index: 1000;
    }

    .fade-enter-active, .fade-leave-active {
        transition: opacity .5s;
    }

    .fade-enter, .fade-leave-to {
        opacity: 0;
    }

    @keyframes alert {
        0% {
            background: $blue;
        }
        50% {
            background: $red;
        }
        100% {
            background: $blue;
        }
    }

    @keyframes alert-countdown {
        0% {
            color: $black;
            background: transparent;
            border-color: $gray-300;
        }
        50% {
            color: $white;
            background: $red;
            border-color: $red;
        }
        100% {
            color: $black;
            background: transparent;
            border-color: $gray-300;
        }
    }
</style>
