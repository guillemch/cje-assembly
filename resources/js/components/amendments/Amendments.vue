<template>
    <div>
        <b-btn id="AmendmentsNewButton" variant="warning" v-b-modal.AmendmentsNew>Nueva votación</b-btn>
        <hr />
        <amendments-list />
        <b-modal id="AmendmentsNew" title="Nueva votación" @shown="$refs.amendmentsForm.autofocus()" :hide-footer="true">
            <amendments-new ref="amendmentsForm" />
        </b-modal>
    </div>
</template>

<script>
    import AmendmentsList from './AmendmentsList';
    import AmendmentsNew from './AmendmentsNew';

    export default {
        name: 'amendments',

        components: {
            AmendmentsList,
            AmendmentsNew
        }
    }
</script>