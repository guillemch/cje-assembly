<template>
    <b-form @submit.prevent="submitAmendment">
        <b-form-group
            id="amendmentNameGroup"
            label="Nombre de la votación"
            label-for="amendmentName"
            :state="errors.hasOwnProperty('name') ? false : null"
            :invalid-feedback="errors.hasOwnProperty('name') ? errors.name[0] : null">
            <b-form-input
                id="amendmentName"
                ref="amendmentName"
                v-model.trim="amendment.name"
                :state="errors.hasOwnProperty('name') ? false : null">
            </b-form-input>
        </b-form-group>

        <b-form-group
            id="amendmentOptionsGroup"
            label="Opciones"
            label-for="amendmentOption1">
            <b-input-group v-for="option in [1, 2, 3, 4, 5]" :key="option">
                <b-input-group-prepend is-text>
                    <input type="checkbox" v-model="amendment['option_' + option + '_active']" :disabled="[1, 2].includes(option)" />
                </b-input-group-prepend>
                <b-form-input
                    :id="'amendmentOption' + option"
                    type="text"
                    v-model.trim="amendment['option_' + option]"
                    :disabled="!amendment['option_' + option + '_active']" />
            </b-input-group>
        </b-form-group>

        <b-form-checkbox
            id="amendmentOpen"
            v-model="amendment.open">
            Abrir votación inmediatamente
        </b-form-checkbox>
        <hr />
        <div class="text-right">
            <b-btn type="button" @click="$root.$emit('bv::hide::modal', 'AmendmentsNew', '#AmendmentsNewButton');">Cancelar</b-btn>
            <b-btn type="submit" variant="primary" :disabled="loading">Crear votación</b-btn>
        </div>
    </b-form>
</template>

<script>
    export default {
        name: 'amendments-new',

        data () {
            return {
                amendment: {
                    name: '',
                    option_1: 'Sí',
                    option_1_active: true,
                    option_2: 'No',
                    option_2_active: true,
                    option_3: 'Abstención',
                    option_3_active: true,
                    option_4: '',
                    option_4_active: false,
                    option_5: '',
                    option_5_active: false,
                    open: true
                },
                loading: false,
                errors: []
            }
        },

        methods: {
            submitAmendment () {
                this.errors = [];
                this.loading = true;

                API.submitAmendment(this.amendment).then(response => {
                    if (this.amendment.open) this.$socket.emit('vote_opened', true);
                    this.resetAmendment();
                    this.$root.$emit('refreshAmendments', true);
                    this.$root.$emit('bv::hide::modal', 'AmendmentsNew', '#AmendmentsNewButton');
                }).catch(errors => {
                    this.errors = errors;
                }).then(() => this.loading = false);
            },

            autofocus () {
                this.$refs.amendmentName.focus();
            },

            resetAmendment() {
                this.amendment.name = '';
                this.option_1 = 'Sí';
                this.option_1_active = true;
                this.option_2 = 'No';
                this.option_2_active = true;
                this.option_3 = 'Abstención';
                this.option_3_active = true;
                this.option_4 = '';
                this.option_4_active = false;
                this.option_5 = '';
                this.option_5_active = false;
                this.open = true;
            }
        }
    }
</script>