<template>
    <div class="amendments-open" v-if="open">
        <b-card class="mb-4" header-bg-variant="success" border-variant="success">
          <h6 slot="header" class="amendments-open__header mb-0">
            <i class="far fa-sync fa-spin"></i> Votación abierta
            <b-btn variant="danger" class="amendments-open__close-button float-right" @click="close">
              <i class="far fa-hand-paper" /> Cerrar
            </b-btn>
          </h6>
          {{ amendment.name }}
        </b-card>
    </div>
</template>

<script>
    export default {
        name: 'amendments-open',

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
                    this.amendment = response[0];
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
          margin: -.75rem;
        }
    }
</style>