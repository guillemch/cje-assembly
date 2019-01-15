<template>
    <b-modal
        id="voteConfirm"
        ref="voteConfirm"
        @shown="focusPassword"
        @hidden="hidden">
        <div slot="modal-title">
            Confirma tu voto
        </div>
        <div :class="['selected--' + optionClass]">
            {{ selectedOption }}
        </div>
        <div slot="modal-footer">
            <b-form @submit.prevent="submitVote">
                <label for="password">
                    Para confirmar tu voto introduce el código que se muestra en pantalla
                </label>
                <b-input-group>
                    <b-form-input v-model="password" id="password" ref="password" type="text" size="lg" class="text-center"></b-form-input>
                    <b-input-group-append>
                        <b-btn type="submit" variant="primary" size="lg">OK</b-btn>
                    </b-input-group-append>
                </b-input-group>
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
                password: ''
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
                this.$emit('submit', this.password);
                this.$refs.voteConfirm.hide();
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