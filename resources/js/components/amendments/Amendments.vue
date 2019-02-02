<template>
    <div class="amendments">
        <div class="amendments-toolbar">
            <div class="container">
                <b-btn id="AmendmentsNewButton" variant="warning" v-b-modal.AmendmentsNew>
                    <i class="far fa-plus" />
                    Nueva votación
                </b-btn>
                <b-btn id="FloorNewButton" variant="info" v-b-modal.FloorNew>
                    <i class="far fa-keynote" />
                    Nuevo turno de palabra
                </b-btn>
                <hr />
                <floor-manager />
                <amendments-open />
            </div>
        </div>
        <amendments-list />
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
