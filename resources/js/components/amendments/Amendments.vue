<template>
    <div class="amendments">
        <div class="amendments-toolbar">
            <div class="container">
                <div class="d-flex mt-2">
                    <b-btn id="AmendmentsNewButton" variant="warning" v-b-modal.AmendmentsNew>
                        <i class="far fa-plus" />
                        Nueva votación
                    </b-btn>
                    <b-btn id="FloorNewButton" variant="info" v-b-modal.FloorNew class="ml-2">
                        <i class="far fa-keynote" />
                        Nuevo turno de palabra
                    </b-btn>
                    <div class="ml-auto form-inline">
                        <b-form-checkbox
                            id="amendmentTimer"
                            v-model="timer.active">
                            Temporizador
                            <input type="time" class="form-control form-control-sm ml-2" v-model="timer.limit" />
                        </b-form-checkbox>
                    </div>
                </div>
                <hr />
                <floor-manager />
                <amendments-open />
            </div>
        </div>
        <amendments-list :timer="timer" />
        <b-modal id="AmendmentsNew" title="Nueva votación" @shown="$refs.amendmentsForm.autofocus()" :hide-footer="true">
            <amendments-new ref="amendmentsForm" />
        </b-modal>
        <b-modal id="FloorNew" title="Nuevo turno de palabra" @shown="$refs.amendmentsForm.autofocus()" :hide-footer="true">
            <floor-new ref="floorForm" />
        </b-modal>
    </div>
</template>

<script>
    import AmendmentsList from './AmendmentsList';
    import AmendmentsOpen from './AmendmentsOpen';
    import AmendmentsNew from './AmendmentsNew';
    import FloorNew from './FloorNew';
    import FloorManager from './FloorManager';

    export default {
        name: 'amendments',

        components: {
            AmendmentsList,
            AmendmentsOpen,
            AmendmentsNew,
            FloorNew,
            FloorManager
        },

        data () {
            return {
                timer: {
                    active: true,
                    limit: '00:30'
                }
            };
        },

        watch: {
            timer: {
                handler: function (newSettings) {
                    localStorage.setItem('timer_settings', JSON.stringify(newSettings));
                },
                deep: true
            }
        },

        mounted () {
            const timerSettings = localStorage.getItem('timer_settings');
            if (timerSettings && timerSettings !== 'null') {
                console.log(timerSettings)
                this.timer = JSON.parse(timerSettings);
            }
        }
    }
</script>


<style lang="scss" scoped>
    @import '~bootstrap/scss/functions';
    @import '~bootstrap/scss/variables';
    @import '~bootstrap/scss/mixins';

    .amendments {
        padding-top: 80px;

        &-toolbar {
            position: fixed;
            background: $gray-100;
            top: 50px;
            left: 0;
            width: 100%;
            padding: 1rem 0;
            z-index: 100;
        }
    }
</style>
