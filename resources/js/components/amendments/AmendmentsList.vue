<template>
    <b-card no-body>
        <h6 slot="header" class="mb-0">Archivo de votaciones</h6>
        <b-table hover :items="amendments" :fields="fields">
            <template slot="table-colgroup">
                <col width="75" />
                <col />
                <col width="150" />
                <col width="140" />
                <col width="140" />
            </template>
            <template slot="winner" slot-scope="data">
                <div :class="'option-tag option-tag-' + data.item.results.winner" v-if="data.item.results.winner">
                    {{ data.item['option_' + data.item.results.winner] }}
                </div>
            </template>
            <template slot="actions" slot-scope="data">
                <div class="text-right">
                    <b-btn size="sm" @click="openVote(data.item)" v-if="data.item.open === 0" :disable="loadingAmendment === data.item.id">
                        <i :class="{ 'far': true, 'fa-hand-paper': loadingAmendment !== data.item.id, 'fa-spinner-third fa-spin': loadingAmendment === data.item.id }" />
                        Abrir
                    </b-btn>
                    <b-btn size="sm" variant="success" disabled v-else>
                        Abierta
                    </b-btn>
                    <b-btn variant="primary" size="sm" @click="fullResults(data.item.id)">+</b-btn>
                </div>
            </template>
        </b-table>

        <b-modal id="AmendmentsDetails" ref="AmendmentsDetails" title="Resultados" size="lg" ok-only ok-title="Cerrar">
            <amendments-results :amendment="selectedAmendment" full-list />
        </b-modal>
    </b-card>
</template>

<script>
    import AmendmentsResults from './AmendmentsResults';
    const moment = require('moment');
    moment.locale('es');

    export default {
        name: 'amendments-list',

        components: {
            AmendmentsResults
        },

        props: {
            timer: Object
        },

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
                        sortable: false
                    },
                    {
                        key: 'closed_at',
                        label: 'Cerrada',
                        formatter: 'dateTime',
                        sortable: true
                    },
                    {
                        key: 'actions',
                        label: ''
                    }
                ],
                amendments: [],
                selectedAmendment: null,
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
                    if(this.timer.active) {
                        const time = this.timer.limit.split(":");
                        const milliseconds = ((parseInt(time[0]) * 60) + (parseInt(time[1]))) * 1000;
                        this.$socket.emit('new_speaker', {
                            speaker: null,
                            time: milliseconds
                        });
                    }
                    // window.scrollTo(0, 0);
                }).catch(error => {
                    console.log(error)
                    alert('Error al cargar las enmiendas. Refresca el navegador');
                }).then(() => this.loadingAmendment = false);
                
            },

            fullResults (amendmentId) {
                API.fullResults(amendmentId).then(response => {
                    this.selectedAmendment = response;
                    this.$refs.AmendmentsDetails.show();
                });
            },
            
            dateTime (value) {
                if (!value) return '';
                return moment(value).format("ddd HH:mm");
            }
        }
    }
</script>