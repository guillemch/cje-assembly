<template>
    <b-card>
        <h6 slot="header" class="mb-0">Archivo de votaciones</h6>
        <b-table striped hover :items="amendments" :fields="fields">
            <template slot="actions" slot-scope="data">
                <b-btn size="sm" @click="openVote(data.item)" v-if="data.item.open === 0" :disable="loadingAmendment === data.item.id">
                    <i :class="{ 'far': true, 'fa-hand-paper': loadingAmendment !== data.item.id, 'fa-spinner-third fa-spin': loadingAmendment === data.item.id }" />
                    Abrir
                </b-btn>
                <span v-else>
                    Votación actual
                </span>
            </template>
        </b-table>
    </b-card>
</template>

<script>
    export default {
        name: 'amendments-list',

        data () {
            return {
                fields: [
                    {
                        key: 'id',
                        label: '#',
                        sortable: true
                    },
                    {
                        key: 'name',
                        label: 'Votación',
                        sortable: true
                    },
                    {
                        key: 'option_1',
                        label: '1/Sí',
                        sortable: true
                    },
                    {
                        key: 'option_2',
                        label: '2/No',
                        sortable: true
                    },
                    {
                        key: 'option_3',
                        label: '3/Abs',
                        sortable: true
                    },
                    {
                        key: 'option_4',
                        label: '4',
                        sortable: true
                    },
                    {
                        key: 'option_5',
                        label: '5',
                        sortable: true
                    },
                    {
                        key: 'closed_at',
                        label: 'Cerrada',
                        sortable: true
                    },
                    {
                        key: 'actions',
                        label: 'Acciones'
                    }
                ],
                amendments: [],
                loadingAmendment: false
            }
        },

        mounted () {
            this.getAmendments();
            this.$root.$on('refreshAmendments', this.getAmendments);
        },

        sockets: {
            refresh_vote: function (data) {
                this.getAmendments();
            }
        },

        methods: {
            getAmendments () {
                API.getAmendments().then(response => {
                    this.amendments = response;
                }).catch(error => {
                    alert('Error al cargar las enmiendas. Refresca el navegador');
                });
            },

            openVote (amendment) {
                this.loadingAmendment = amendment.id;
                API.openAmendment(amendment.id).then(response => {
                    this.$socket.emit('vote_opened', true);
                    window.scrollTo(0, 0);
                }).catch(error => {
                    alert('Error al cargar las enmiendas. Refresca el navegador');
                }).then(() => this.loadingAmendment = false);
                
            }
        }
    }
</script>