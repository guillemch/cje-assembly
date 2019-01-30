<template>
    <div class="screen">
        <transition name="fade">
            <div v-if="screen.vote !== null" class="vote-info">
                <div class="vote-name">
                    <h1>{{ screen.vote.name }}</h1>
                </div>
                <div class="vote-results">
                    <screen-results :amendment="screen.vote" />
                    <screen-results-by-group :results="screen.vote.results.by_group" :compensate="screen.vote.results.compensate" />
                </div>
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
        <div :class="{ 'screen-logo': true, 'vote-active': screen.vote !== null }">
            <div class="logo">
                <img src="../../../images/logo.jpg" alt="Logo" />
            </div>
        </div>
    </div>
</template>

<script>
    import ScreenResults from './ScreenResults';
    import ScreenResultsByGroup from './ScreenResultsByGroup';
    import ScreenCode from './ScreenCode';

    export default {
        name: 'screen',

        components: {
            ScreenResults,
            ScreenResultsByGroup,
            ScreenCode
        },

        data () {
            return {
                screen: { vote: null, code: null, just_closed: false, next_alert: false },
                loading: false,
                connected: false,
                interval: null,
                ticking: false
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
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import '../../../sass/variables';
    @import '~bootstrap/scss/functions';
    @import '~bootstrap/scss/variables';
    @import '~bootstrap/scss/mixins';

    .screen {
    /*    width: 1080px;
        height: 900px;
        border: 2px red solid; */

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

        .vote-info {
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
</style>
