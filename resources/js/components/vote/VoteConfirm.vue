<template>
    <b-modal
        id="voteConfirm"
        ref="voteConfirm"
        @shown="focusPassword"
        @hidden="hidden">
        <div slot="modal-title">
            Confirma tu voto
        </div>
        <div class="vote">
            <div class="vote__icon" v-if="userVotes === 1">
                <i class="hand far fa-hand-paper" />
            </div>
            <ul :class="['vote__summary', {'vote__summary--multiple': userVotes > 1}]">
                <li v-for="vote in votes" :key="vote.id">
                    <span class="vote__summary__name">{{ vote.name }}</span>
                    <span class="vote__summary__divider"></span>
                    <div v-if="Object.values(selected[vote.id]).some((votes) => votes > 0)" class="vote__summary__selection">
                        <div v-if="Object.values(selected[vote.id]).reduce((a, b) => a + b) < userVotes">
                            <div class="alert alert-sm alert-info">
                                ℹ️ No has asignado todos tus votos
                            </div>
                        </div>
                        <div v-else-if="Object.values(selected[vote.id]).reduce((a, b) => a + b) > userVotes">
                            <div class="alert alert-sm alert-danger">
                                🛑 Has asignado más votos de los disponibles
                            </div>
                        </div>
                        <ul>
                            <template v-for="(votes, key) in selected[vote.id]">
                                <li v-if="(userVotes > 1 && vote[key]) || (userVotes === 1 && votes === 1)" :key="key">
                                    <span v-if="userVotes > 1">
                                        {{ vote[key] }}:
                                        {{ votes }}
                                    </span>
                                    <span v-else :class="key">
                                        {{ vote[key] }}
                                    </span>
                                </li>
                            </template>
                        </ul>
                    </div>
                    <span v-else class="vote__summary__ignore">No votar</span>
                </li>
            </ul>
        </div>
        <div slot="modal-footer" class="footer text-center">
            <b-form @submit.prevent="submitVote" v-if="canVote">
                <label for="password">
                    <small class="text-muted">Para confirmar tu voto introduce el código que se muestra en pantalla</small>
                </label>
                <b-input-group>
                    <b-form-input
                        v-model="password"
                        id="password"
                        ref="password" 
                        type="text"
                        size="lg"
                        class="text-center"
                        autocomplete="off"
                        pattern="[0-9]*"
                        maxlength="6"
                        required>
                    </b-form-input>
                    <b-input-group-append>
                        <b-btn
                            type="submit"
                            variant="primary"
                            size="lg">
                            <i class="far fa-check" />
                        </b-btn>
                    </b-input-group-append>
                </b-input-group>
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
        name: 'vote-confirm',

        props: {
            votes: Array,
            selected: Object,
            canVote: Boolean
        },

        data () {
            return {
                password: '',
                loading: false,
                errors: [],
                userVotes: window.user.votes
            };
        },

        methods: {
            submitVote () {
                const { selected, password } = this;
                const amendment_id = this.vote.id;

                this.loading = true;
                this.errors = [];

                API.submitVote({
                    amendment_id,
                    selected,
                    password
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

            hidden () {
                this.password = '';
            },

            focusPassword () {
                this.$refs.password.focus();
            }
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

        &__summary {
            margin: 0;
            padding: 0;
            list-style: none;

            & > li {
                display: flex;
                margin: 1rem 0;
            }

            &__divider {
                border-bottom: 1px dotted $gray-500;
                flex-grow: 1;
                flex-shrink: 0;
                position: relative;
                top: -5px;
                margin: 0 .5rem;
            }

            &__selection ul {
                margin: 0;
                padding: 0;
                list-style: none;
            }

            &__ignore {
                color: $gray-600;
                font-style: italic;
            }

            &--multiple {
                & > li {
                    flex-direction: column;
                }

                .vote__summary__divider {
                    display: none;
                }

                .vote__summary__name {
                    font-weight: bold;
                    border-bottom: 1px dotted $gray-500;
                    margin-bottom: .5rem;
                }
            }

            @each $name, $color in $colors {
                .#{$name} {
                    color: $color;
                }
            }
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