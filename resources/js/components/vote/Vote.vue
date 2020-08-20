<template>
    <div class="vote">
        <div class="vote__status">
            <div class="container d-flex align-items-center">
                <div class="connected" v-if="connected">
                    Conectado
                </div>
                <div  class="disconnected" v-else>
                    Desconectado
                </div>
                <b-btn @click="getOpenVotes" size="sm" class="ml-auto">
                    <i class="far fa-sync" />
                </b-btn>
            </div>
        </div>
        <div v-if="loading" class="vote__placeholder">
            <i class="far fa-sync fa-spin"></i>
            Cargando...
        </div>
        <div v-else>
            <div v-if="!votes || votes.length === 0" class="vote__placeholder">
                <i class="far fa-mug-hot" />
                Ninguna votación en curso
            </div>
            <vote-open v-else :votes="votes" @refresh="getOpenVotes" />
        </div>
    </div>
</template>

<script>
    import VoteOpen from './VoteOpen';

    export default {
        name: 'vote',

        components: {
            VoteOpen
        },

        data () {
            return {
                votes: null,
                loading: false,
                connected: false
            };
        },

        mounted () {
            this.getOpenVotes();
        },

        sockets: {
            connect: function () {
                this.connected = true;
                this.getOpenVotes();
            },
            refresh_vote: function (data) {
                this.getOpenVotes();
            },
            disconnect: function () {
                this.connected = false;
            }
        },

        methods: {
            getOpenVotes () {
                this.loading = true;

                API.getOpenVotes().then(votes => {
                    this.votes = (votes.length > 0) ? votes : null;
                }).catch(error => {
                    alert('Error');
                }).then(() => this.loading = false);
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import '~bootstrap/scss/functions';
    @import '~bootstrap/scss/variables';
    @import '~bootstrap/scss/mixins';
    
    .vote {
        padding-top: 4rem;

        &__placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: $gray-500;
        font-size: 2rem;
        height: 40vh;
        text-align: center;
        line-height: 1.15;

            .far {
                font-size: 4rem;
                margin-bottom: 2rem;
            }
        }

        &__status {
            position: fixed;
            top: 57px;
            left: 0;
            right: 0;
            background: $gray-300;
            border-bottom: 1px $gray-400 solid;
            padding: .5rem;
            z-index: 1000;

            .connected {
                color: $green;
                font-weight: bold;
            }

            .disconnected {
                color: $red;
                font-weight: bold;
            }
        }
    }
</style>