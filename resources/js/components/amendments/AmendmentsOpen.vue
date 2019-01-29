<template>
    <div class="amendments-open" v-if="amendment">
        <b-card class="mb-4" header-bg-variant="success" border-variant="success">
          <h6 slot="header" class="amendments-open__header mb-0">
            <i class="far fa-sync fa-spin mr-2"></i> Votación abierta
            <div class="float-right">
                <amendments-timer :opened="amendment.opened_at" />
                <b-btn variant="danger" class="amendments-open__close-button" @click="close" :disabled="closing">
                    <i class="far fa-hand-paper" v-if="!closing" />
                    <i class="fa-spinner-third fa-spin" v-else />
                    Cerrar
                </b-btn>
            </div>
          </h6>

          <amendments-results :amendment="amendment" />
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
                amendment: null,
                interval: null,
                closing: false
            }
        },

        mounted () {
            this.getCurrentVote(true);
        },

        sockets: {
            refresh_vote: function (data) {
                this.getCurrentVote(true);
            }
        },

        methods: {
            getCurrentVote (refresh) {
                API.getCurrentVote({ with_results: true }).then(vote => {
                    if (vote.hasOwnProperty('name')) {
                        this.amendment = vote;
                        if (refresh) this.interval = setInterval(() => { this.getCurrentVote(false) }, 2000);
                    } else {
                        this.amendment = null;
                        clearInterval(this.interval);
                        this.interval = null;
                    }
                }).catch(error => {
                    alert('Error');
                });
            },

            close () {
                this.closing = true;

                API.closeAmendment(this.amendment.id).then(response => {
                    this.$socket.emit('vote_opened', false);
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
    }
</style>