<template>
    <b-modal
        id="voteConfirm"
        ref="voteConfirm"
        @shown="focusPassword"
        @hidden="hidden"
        header-close-label="Cerrar confirmación de voto">
        <div slot="modal-title">
            Confirma tu voto
        </div>
        <div class="vote">
            <div class="vote__icon" v-if="userVotes === 1" aria-hidden="true">
                <i class="hand far fa-hand-paper" />
            </div>
            <vote-summary :votes="votes" :selected="selected" />
        </div>
        <div slot="modal-footer" class="footer text-center">
            <b-form @submit.prevent="submitVote" v-if="canVote">
                <label for="password" v-if="!codeExemption">
                    <small class="text-muted">Para confirmar tu voto introduce el código que se muestra en pantalla</small>
                </label>
                <b-input-group v-if="!codeExemption">
                    <b-form-input
                        v-model="password"
                        id="password"
                        ref="password" 
                        type="text"
                        size="lg"
                        class="text-center"
                        aria-describedby="errors"
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
                            <span class="sr-only">Emitir voto</span>
                        </b-btn>
                    </b-input-group-append>
                </b-input-group>
                <div v-else>
                    <b-btn
                        ref="submit"
                        type="submit"
                        variant="primary"
                        size="lg">
                        <i class="far fa-check" />
                        Emitir voto
                    </b-btn>
                </div>
                <div v-if="errors" id="errors" aria-live="assertive" role="alert">
                    <div v-for="(error, key) in errors" :key="key" class="alert alert-danger mt-3 mb-0">
                        {{ error[0] }}
                    </div>
                </div>
            </b-form>
            <div v-else id="ballot-errors">
                Corrige los errores para poder votar
            </div>
        </div>
    </b-modal>
</template>

<script>
    import VoteSummary from './VoteSummary.vue';

    export default {
        name: 'vote-confirm',

        components: {
            VoteSummary
        },

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
                userVotes: window.user.votes,
                codeExemption: window.user.code_exemption
            };
        },

        methods: {
            submitVote () {
                const { selected, password } = this;
                this.loading = true;
                this.errors = [];

                API.submitVote({
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
                if (this.codeExemption) {
                    this.$refs.submit.focus();
                } else {
                    this.$refs.password.focus();
                }
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