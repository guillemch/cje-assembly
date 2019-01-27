<template>
    <div class="amendments-open" v-if="amendment">
        <b-card class="mb-4" header-bg-variant="success" border-variant="success">
          <h6 slot="header" class="amendments-open__header mb-0">
            <i class="far fa-sync fa-spin mr-2"></i> Votación abierta
            <div class="float-right">
                <amendments-timer />
                <b-btn variant="danger" class="amendments-open__close-button" @click="close">
                    <i class="far fa-hand-paper" /> Cerrar
                </b-btn>
            </div>
          </h6>

          <h4>{{ amendment.name }}</h4>

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
                amendment: null
            }
        },

        mounted () {
            this.getCurrentVote();
        },

        sockets: {
            refresh_vote: function (data) {
                this.getCurrentVote();
            }
        },

        methods: {
            getCurrentVote () {
                API.getCurrentVote({ with_results: true }).then(vote => {
                    this.amendment = (vote.hasOwnProperty('name')) ? vote : null;
                }).catch(error => {
                    alert('Error');
                });
            },

            close () {
                API.closeAmendment(this.amendment.id).then(response => {
                    this.$socket.emit('vote_opened', false);
                    this.amendment = {};
                }).catch(error => {
                    alert('Error');
                });
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
          margin: -.8rem 1rem;
        }
    }
</style>