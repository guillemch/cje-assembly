<template>
    <b-card>
        <h6 slot="header" class="mb-0">Archivo de votaciones</h6>
        <b-table hover :items="amendments" :fields="fields">
            <template slot="table-colgroup">
                <col width="5%" />
                <col />
                <col width="15%" />
                <col width="10%" />
                <col width="10%" />
            </template>
            <template slot="winner" slot-scope="data">
                <div :class="'option-tag option-tag-' + data.item.results.winner" v-if="data.item.results.winner">
                    {{ data.item['option_' + data.item.results.winner] }}
                </div>
            </template>
            <template slot="actions" slot-scope="data">
                <b-btn size="sm" @click="openVote(data.item)" v-if="data.item.open === 0" :disable="loadingAmendment === data.item.id">
                    <i :class="{ 'far': true, 'fa-hand-paper': loadingAmendment !== data.item.id, 'fa-spinner-third fa-spin': loadingAmendment === data.item.id }" />
                    Abrir
                </b-btn>
                <span v-else>
                    Abierta
                </span>
            </template>
        </b-table>
    </b-card>
</template>

<script>
    import dateFormat from 'dateformat';

    dateFormat.i18n = {
        dayNames: [
            'Dom', 'Lun', 'Mar', 'Mié', 'Jue', 'Vie', 'Sáb',
            'Domingo', 'Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'
        ],
        monthNames: [
            'Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic',
            'Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio', 'Julio', 'Agosto', 'Septiembre', 'Octubre', 'Noviembre', 'Diciembre'
        ],
        timeNames: [
            'a', 'p', 'am', 'pm', 'A', 'P', 'AM', 'PM'
        ]
    };

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
                        key: 'winner',
                        label: 'Resultado',
                        sortable: true
                    },
                    {
                        key: 'closed_at',
                        label: 'Cerrada',
                        formatter: 'dateTime',
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
                
            },
            
            dateTime (value) {
                if (!value) return '';
                const date = new Date(value);
                return dateFormat(date, "ddd HH:MM");
            }
        }
    }
</script>