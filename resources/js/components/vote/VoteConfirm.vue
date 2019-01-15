<template>
    <b-modal
        id="voteConfirm"
        ref="voteConfirm"
        @shown="focusPassword"
        @hidden="hidden">
        <div slot="modal-title">
            Confirma tu voto
        </div>
        <div :class="['selected', 'selected--' + optionClass]">
            {{ selectedOption }}
        </div>
        <div slot="modal-footer">
            <b-form @submit.prevent="submitVote">
                <label for="password">
                    Para confirmar tu voto introduce el código que se muestra en pantalla
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
                            OK
                        </b-btn>
                    </b-input-group-append>
                </b-input-group>
                <div v-if="errors">
                    <div v-for="(error, key) in errors" :key="key" class="alert alert-danger mt-3 mb-0">
                        {{ error[0] }}
                    </div>
                </div>
            </b-form>
        </div>
    </b-modal>
</template>

<script>
    export default {
        name: 'vote-confirm',

        props: {
            vote: Object,
            selected: Number
        },

        data () {
            return {
                password: '',
                loading: false,
                errors: []
            };
        },

        computed: {
            selectedOption: function () {
                return this.vote['option_' + this.selected];
            },

            optionClass: function () {
                const classes = {
                    1: 'success',
                    2: 'danger',
                    3: 'warning',
                    4: 'info',
                    5: 'secondary'
                };

                return classes[this.selected];
            }
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