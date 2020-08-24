<template>
    <div class="amendments-open" v-if="amendments">
        <b-card
            class="mb-4"
            header-bg-variant="success"
            border-variant="success"
            no-body>
            <h6 slot="header" class="amendments-open__header mb-0">
                <i class="far fa-sync fa-spin mr-2"></i> Votación abierta
                <div class="float-right">
                    <amendments-timer :opened="amendments[0].opened_at" />
                    <b-btn variant="danger" class="amendments-open__close-button" @click="close" :disabled="closing">
                        <i class="far fa-hand-paper" v-if="!closing" />
                        <i class="fa-spinner-third fa-spin" v-else />
                        Cerrar
                    </b-btn>
                    <b-btn variant="success" class="amendments-open__minimize-button" @click="minimized = !minimized">
                        <i class="far fa-window-minimize" v-if="!minimized" />
                        <i class="far fa-window-maximize" v-else />
                    </b-btn>
                </div>
            </h6>
            <b-tabs card v-show="!minimized">
                <b-tab
                    v-for="amendment in amendments"
                    :key="amendment.id"
                    :title="amendment.name">
                        <amendments-results :amendment="amendment" />
                </b-tab>
            </b-tabs>
         </b-card>
    </div>
</template>

<script>
    import AmendmentsTimer from './AmendmentsTimer';
    import AmendmentsResults from './AmendmentsResults';

    export default {
        name: 'amendments-open',

        components: {
            AmendmentsTimer,
            AmendmentsResults
        },

        data () {
            return {
                amendments: null,
                interval: null,
                closing: false,
                minimized: false
            }
        },

        mounted () {
            this.getOpenVotes(true);
        },

        sockets: {
            refresh_vote: function (data) {
                this.getOpenVotes(true);
            }
        },

        methods: {
            getOpenVotes (refresh) {
                API.getOpenVotes({ with_results: true }).then(amendments => {
                    if (amendments.length > 0) {
                        this.amendments = amendments;
                        if (refresh) this.interval = setInterval(() => { this.getOpenVotes(false) }, 2000);
                    } else {
                        this.amendments = null;
                        clearInterval(this.interval);
                        this.interval = null;
                    }
                }).catch(error => {
                    alert('Error');
                });
            },

            close () {
                this.closing = true;

                API.closeAmendment(this.amendments[0].id).then(response => {
                    this.$socket.emit('vote_opened', false);
                    this.$socket.emit('new_speaker', {
                        speaker: null,
                        time: null
                    });
                    this.amendment = null;
                    clearInterval(this.interval);
                    this.interval = null;
                }).catch(error => {
                    alert('Error');
                }).then(() => this.closing = false);
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import '~bootstrap/scss/functions';
    @import '~bootstrap/scss/variables';
    @import '~bootstrap/scss/mixins';

    .amendments-open {
        &__header {
          font-weight: bold;
          color: $white;
        }

        &__close-button {
          margin: -.9rem 0 -.8rem 1rem;
        }

        &__minimize-button {
            margin: -.9rem -.9rem -.8rem 0;
        }
    }
</style>
<style>
    .nav-tabs .nav-link.active, .nav-tabs .nav-item.show .nav-link {
        background: white;
    }
</style>