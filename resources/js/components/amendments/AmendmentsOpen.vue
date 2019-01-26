<template>
    <div class="amendments-open" v-if="open">
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
          {{ amendment.name }}
        </b-card>
    </div>
</template>

<script>
    import AmendmentsTimer from './AmendmentsTimer';

    export default {
        name: 'amendments-open',

        components: {
            AmendmentsTimer
        },

        data () {
            return {
                open: false,
                amendment: {},
                votes: []
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
                API.getCurrentVote().then(response => {
                    this.open = (response.length > 0) ? true : false;
                    this.amendment = (response.length > 0) ? response[0] : {};
                }).catch(error => {
                    alert('Error');
                });
            },

            close () {
                API.closeAmendment(this.amendment.id).then(response => {
                    this.$socket.emit('vote_opened', false);
                    this.open = false;
                    this.amendment = {};
                    this.votes = [];
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