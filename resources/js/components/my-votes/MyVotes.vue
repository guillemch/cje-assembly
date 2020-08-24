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
                                <tr :key="'vote-' + vote.id" class="row-my-vote">
                                    <td class="my-vote-time">{{ vote.created_at | time }}</td>
                                    <td class="my-vote-name">{{ vote.amendment.name }}</td>
                                    <td class="my-vote-option">
                                        <div :class="'option-tag option-tag-' + vote.vote_for">
                                            {{ vote.amendment['option_' + vote.vote_for] }}
                                        </div>
                                        <div v-if="vote.votes > 1" class="times-tag">
                                            x {{ vote.votes }}
                                        </div>
                                    </td>
                                    <td class="my-vote-result">
                                        <div v-if="vote.amendment.results.winner && vote.amendment.closed_at" :class="'option-tag option-tag-' + vote.amendment.results.winner">
                                            {{ vote.amendment['option_' + vote.amendment.results.winner] }}
                                        </div>
                                        <div v-else-if="vote.amendment.closed_at === null">
                                            <em>En curso</em>
                                        </div>
                                    </td>
                                    <td class="my-vote-more text-right">
                                        <a v-if="vote.amendment.closed_at" href="#" @click.prevent="fullResults(vote.amendment.id)" class="btn btn-sm" aria-label="Detalles">
                                            <span class="d-none d-lg-inline">+</span>
                                            <span class="d-lg-none">Detalles</span>
                                        </a>
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

        .my-vote-option {
            display: grid;
            grid-template-columns: 1fr auto;
            align-items: center;
            gap: .5rem;

            .times-tag {
                white-space: nowrap;
                color: $gray-600;
                min-width: 35px;
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

    @include media-breakpoint-down(md) {
        .my-votes .table {
            thead {
                display: none;
            }

            .row-date {
                display: block;

                th {
                    display: block;
                    width: 100%;
                }
            }

            .row-my-vote {
                display: grid;
                grid-template-columns: 75px 1fr 1fr 75px;
                grid-template-areas:
                    "time name name more"
                    "my-option my-option result result";

                .my-vote-time {
                    grid-area: time;
                }

                .my-vote-name {
                    grid-area: name;
                }

                .my-vote-more {
                    grid-area: more;
                    padding-top: 0;
                    padding-bottom: 0;
                    display: flex;
                    align-items: center;
                    justify-content: flex-end;

                    .btn {
                        text-decoration: underline;
                    }
                }

                .my-vote-option {
                    grid-area: my-option;
                    border-top: 0;
                    padding-top: 0;

                    &::before {
                        content: "Mi voto";
                        color: $gray-600;
                        font-size: .85rem;
                        grid-column: span 2;
                    }
                }

                .my-vote-result {
                    grid-area: result;
                    border-top: 0;
                    padding-top: 0;

                    &::before {
                        content: "Resultado";
                        display: block;
                        color: $gray-600;
                        font-size: .85rem;
                        margin-bottom: .5rem;
                    }
                }
            }
        }

        
    }
</style>