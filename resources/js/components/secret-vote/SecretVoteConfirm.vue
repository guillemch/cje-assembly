<template>
    <b-modal
        id="voteConfirm"
        ref="voteConfirm">
        <div slot="modal-title">
            Confirma tu voto
        </div>
        <div class="vote">
            <div class="vote__icon" v-if="userVotes === 1">
                <i class="hand far fa-ballot" />
            </div>
            <ul>
                <li v-for="vote in votes" :key="vote.id">
                    {{ vote.name }}
                    <ul v-if="Object.values(selected[vote.id]).some((votes) => votes > 0)">
                        <template v-for="option in vote.options">
                            <li :key="option.id" v-if="selected[vote.id][option.id] > 0">
                                {{ option.name }}
                                <span v-if="selected[vote.id][option.id] > 1">
                                    x {{ selected[vote.id][option.id] }}
                                </span>
                            </li>
                        </template>
                    </ul>
                    <div v-else>No votar</div>
                </li>
            </ul>
        </div>
        <div slot="modal-footer" class="footer text-center">
            <b-form @submit.prevent="submitVote" v-if="canVote">
                <b-button
                    type="submit"
                    size="lg"
                    variant="primary"
                    block
                    :disabled="!canVote">
                    🗳 &nbsp; Votar
                </b-button>
                <div v-if="errors">
                    <div v-for="(error, key) in errors" :key="key" class="alert alert-danger mt-3 mb-0">
                        {{ error[0] }}
                    </div>
                </div>
            </b-form>
            <div v-else>
                Corrige los errores para poder votar
            </div>
        </div>
    </b-modal>
</template>

<script>
    export default {
        name: 'secret-vote-confirm',

        props: {
            votes: Array,
            selected: Object,
            canVote: Boolean
        },

        data () {
            return {
                loading: false,
                errors: [],
                userVotes: window.user.votes
            };
        },

        methods: {
            submitVote () {
                const { selected } = this;
                this.loading = true;
                this.errors = [];

                API.submitSecretVote({
                    selected
                }).then(response => {
                    if (response.submitted) {
                        this.$emit('submitted', true);
                        this.$refs.voteConfirm.hide();
                    } else {
                        alert('Error inesperado');
                    }
                }).catch(errors => {
                    if (errors.hasOwnProperty('message')) {
                        this.errors = [{'global': [errors.message]}];
                    } else {
                        this.errors = errors;
                    }
                }).then(() => this.loading = false);
            },
        }
    }
</script>

<style lang="scss" scoped>
    @import '../../../sass/variables';
    @import '~bootstrap/scss/functions';
    @import '~bootstrap/scss/variables';
    @import '~bootstrap/scss/mixins';

    .vote {
        &__icon {
            text-align: center;
            font-size: 2.5rem;
        }
    }

    .hand {
        color: $gray-700;
        animation: thumbs-up 1s ease;
    }

    .footer {
        width: 100%;

        label {
            line-height: 1;
        }
    }

    @keyframes thumbs-up {
        0% {
            opacity: 0;
            transform: translate(0, 75px) rotate(20deg);
        }
        50% {
            opacity: 1;
            transform: translate(0, -50px) rotate(-20deg);
        }
        100% {
            opacity: 1;
            transform: translate(0, 0) rotate(0);
        }
    }
</style>