<template>
    <div>
        {{ amendments }}
    </div>
</template>

<script>
    export default {
        name: 'amendments-list',

        data () {
            return {
                amendments: []
            }
        },

        mounted () {
            this.getAmendments();
        },

        methods: {
            getAmendments () {
                API.getAmendments().then(response => {
                    this.amendments = response;
                }).catch(error => {
                    alert('Error al cargar las enmiendas. Refresca el navegador');
                });
            }
        }
    }
</script>