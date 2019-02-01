<template>
    <div v-if="time" class="floor">
        <b-card class="mb-4" header-bg-variant="info" border-variant="info">
            <h6 slot="header" class="mb-0">
                <i class="far fa-sync fa-spin mr-2"></i> Turno de palabra
                <div class="float-right">
                    <b-btn variant="danger" class="close-button" @click="close">
                        <i class="far fa-hand-paper" />
                        Cerrar
                    </b-btn>
                </div>
            </h6>
            <div class="row align-items-center">
                <div class="col-md-8">
                    <h5 v-if="speaker">{{ speaker.name }} ({{ speaker.group.acronym }})</h5>
                </div>
                <div class="col-md-4 text-right">
                    <countdown ref="countdown" :time="time" class="countdown" @end="reset">
                        <template slot-scope="props">{{ props.minutes | time }}:{{ props.seconds | time }}</template>
                    </countdown>
                </div>
            </div>
        </b-card>
    </div>
</template>

<script>
    import VueCountdown from '@chenfengyuan/vue-countdown';

    export default {
        name: 'floor-manager',

        components: {
            'countdown': VueCountdown
        },

        data () {
            return {
                time: null,
                speaker: null,
                connected: false
            }
        },

        sockets: {
            connect: function () {
                this.connected = true;
            },
            refresh_speaker: function (payload) {
                this.time = payload.time;

                if (payload.speaker) {
                    this.speaker = payload.speaker;
                } else {
                    this.speaker = null;
                }
            },
            disconnect: function () {
                this.connected = false;
            }
        },

        methods: {
            close () {
                this.$socket.emit('new_speaker', {
                    speaker: null,
                    time: null
                });
            },

            reset () {
                setTimeout(() => {
                    this.time = null;
                    this.speaker = null;
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

    .floor {
        h6 {
            color: $white;
            font-weight: bold;
        }

        .close-button {
            margin: -.9rem 0 -.8rem 1rem;
        }

        .countdown {
            display: inline-block;
            background: $gray-200;
            font-size: 2rem;
            padding: .5rem 1.5rem;
            border-radius: .5rem;
            min-width: 12rem;
            text-align: center;
        }
    }
</style>