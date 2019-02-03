<template>
    <div class="my-votes">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <h2><i class="far fa-box-ballot"></i> Mis votos</h2>

                <div class="card">
                    <div class="card-body p-0">
                        <table class="table table-my-votes table-responsive-sm">
                            <colgroup>
                                <col width="100" />
                                <col />
                                <col width="150" />
                                <col width="150" />
                                <col width="75" />
                            </colgroup>
                            <thead>
                                <tr>
                                    <th>Hora</th>
                                    <th>Votación</th>
                                    <th>Mi voto</th>
                                    <th colspan="2">Resultado</th>
                                </tr>
                            </thead>
                            <template v-for="(vote, i) in votes">
                                <tr v-if="(i === 0) ? true : (compareDate(votes[(i - 1)].created_at) !== compareDate(vote.created_at))" class="row-date thead-light" :key="'header-' + vote.id">
                                    <th colspan="5">{{ vote.created_at | fullDate }}</th>
                                </tr>
                                <tr :key="'vote-' + vote.id">
                                    <td>{{ vote.created_at | time }}</td>
                                    <td>{{ vote.amendment.name }}</td>
                                    <td>
                                        <div :class="'option-tag option-tag-' + vote.vote_for">
                                            {{ vote.amendment['option_' + vote.vote_for] }}
                                        </div>
                                    </td>
                                    <td>
                                        <div v-if="vote.amendment.results.winner && vote.amendment.closed_at" :class="'option-tag option-tag-' + vote.amendment.results.winner">
                                            {{ vote.amendment['option_' + vote.amendment.results.winner] }}
                                        </div>
                                        <div v-else-if="vote.amendment.closed_at === null">
                                            <em>En curso</em>
                                        </div>
                                    </td>
                                    <td class="text-right">
                                        <a v-if="vote.amendment.closed_at" href="#" @click.prevent="fullResults(vote.amendment.id)" class="btn btn-sm">+</a>
                                    </td>
                                </tr>
                            </template>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <b-modal id="AmendmentsDetails" ref="AmendmentsDetails" title="Resultados" size="lg" ok-only ok-title="Cerrar">
            <amendments-results :amendment="selectedAmendment" />
        </b-modal>
    </div>
</template>

<script>
    import AmendmentsResults from '../amendments/AmendmentsResults';
    const moment = require('moment');
    moment.locale('es');

    export default {
        name: 'my-votes',

        components: {
            AmendmentsResults
        },

        data () {
            return {
                votes: null,
                loading: false,
                selectedAmendment: null
            };
        },

        mounted () {
            this.getMyVotes();
        },

        filters: {
            fullDate: function (value) {
                if (!value) return '';
                return moment(value).format("dddd, D MMMM YYYY");
            },

            time: function (value) {
                if (!value) return '';
                return moment(value).format("HH:mm");
            }
        },

        methods: {
            getMyVotes () {
                this.loading = true;

                API.getMyVotes().then(votes => {
                    this.votes = votes;
                }).catch(error => {
                    alert('Error al cargar las votaciones');
                }).then(() => this.loading = false);
            },

            fullResults (amendmentId) {
                API.results(amendmentId).then(response => {
                    this.selectedAmendment = response;
                    this.$refs.AmendmentsDetails.show();
                });
            },

            compareDate (date) {
                return moment(date).format("dddd, D MMMM YYYY");
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import '../../../sass/variables';
    @import '~bootstrap/scss/functions';
    @import '~bootstrap/scss/variables';
    @import '~bootstrap/scss/mixins';
    
    .my-votes {
      h2 {
        color: $primary;
        font-size: 2.5rem;
        margin-top: 2rem;
      }

      .table {
        .row-date {
          th {
            padding: .5rem .75rem;
            font-size: .8rem;
          }
        }
      }
    }

    @include media-breakpoint-up(md) {
      .vote-archive {
        h2 {
          font-size: 4rem;
        }
      }
    }
</style>