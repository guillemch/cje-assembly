<template>
    <b-card no-body>
        <h6 slot="header" class="mb-0">Archivo de votaciones</h6>
        <b-table-simple hover>
            <colgroup>
                <col width="75" />
                <col />
                <col width="150" />
                <col width="140" />
                <col width="140" />
            </colgroup>
            <b-thead>
                <b-th class="text-right">#</b-th>
                <b-th>Votación</b-th>
                <b-th>Resultado</b-th>
                <b-th>Cerrada</b-th>
                <b-th></b-th>
            </b-thead>
            <b-tbody>
                <template v-for="amendment in amendments">
                    <b-tr :key="amendment.id">
                        <b-td class="text-right">
                            {{ amendment.id }}
                        </b-td>
                        <b-td>
                            {{ amendment.name }}
                        </b-td>
                        <b-td>
                            <div :class="'option-tag option-invert option_' + amendment.results.winner" v-if="amendment.results.winner">
                                {{ amendment['option_' + amendment.results.winner] }}
                            </div>
                        </b-td>
                        <b-td>
                            {{ amendment.closed_at | dateTime }}
                        </b-td>
                        <b-td>
                            <div class="text-right">
                                <b-btn size="sm" @click="openVote(amendment)" v-if="amendment.open === 0" :disable="loadingAmendment === amendment.id">
                                    <i :class="{ 'far': true, 'fa-hand-paper': loadingAmendment !== amendment.id, 'fa-spinner-third fa-spin': loadingAmendment === amendment.id }" />
                                    Abrir
                                </b-btn>
                                <b-btn size="sm" variant="success" disabled v-else>
                                    Abierta
                                </b-btn>
                                <b-btn variant="primary" size="sm" @click="fullResults(amendment.id)">+</b-btn>
                            </div>
                        </b-td>
                    </b-tr>
                    <b-tr v-for="jointAmendment in amendment.joint_amendments" :key="jointAmendment.id" class="joint-amendment">
                        <b-td class="text-right">
                            {{ jointAmendment.id }}
                        </b-td>
                        <b-td>
                            <i class="far fa-arrow-right"></i>
                            {{ jointAmendment.name }}
                        </b-td>
                        <b-td>
                            <div :class="'option-tag option-invert option_' + jointAmendment.results.winner" v-if="jointAmendment.results.winner">
                                {{ jointAmendment['option_' + jointAmendment.results.winner] }}
                            </div>
                        </b-td>
                        <b-td>
                            
                        </b-td>
                        <b-td>
                            <div class="text-right">
                                <b-btn variant="primary" size="sm" @click="fullResults(jointAmendment.id)">+</b-btn>
                            </div>
                        </b-td>
                    </b-tr>
                </template>
            </b-tbody>
        </b-table-simple>

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
            }
        },

        filters: {
            dateTime (value) {
                if (!value) return '';
                return moment(value).format("ddd HH:mm");
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import '../../../sass/variables';
    @import '~bootstrap/scss/functions';
    @import '~bootstrap/scss/variables';
    @import '~bootstrap/scss/mixins';

    .joint-amendment {
        td {
            border-top: 0;
            color: $gray-600;

            i {
                margin-right: .5rem;
            }
        }
    }
</style>
