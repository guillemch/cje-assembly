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
                                <tr v-if="(i === 0) ? true : (votes[(i - 1)].created_at !== vote.created_at)" class="row-date thead-light" :key="'header-' + vote.id">
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
                                        <div v-if="vote.amendment.results.winner" :class="'option-tag option-tag-' + vote.amendment.results.winner">
                                            {{ vote.amendment['option_' + vote.amendment.results.winner] }}
                                        </div>
                                    </td>
                                    <td class="text-right">
                                        <a href="#" @click.prevent="fullResults(vote.amendment.id)" class="btn btn-sm">+</a>
                                    </td>
                                </tr>
                            </template>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <b-modal id="AmendmentsDetails" ref="AmendmentsDetails" title="Resultados" size="lg" ok-only ok-title="Cerrar">
            <amendments-results :amendment="selectedAmendment" full-list />
        </b-modal>
    </div>
</template>

<script>
    import dateFormat from 'dateformat';
    import AmendmentsResults from '../amendments/AmendmentsResults';
    dateFormat.i18n = require('../../shared/dates.js');

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
                const date = new Date(value);
                return dateFormat(date, "dddd, d mmmm yyyy");
            },

            time: function (value) {
                if (!value) return '';
                const date = new Date(value);
                return dateFormat(date, "HH:MM");
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
                API.fullResults(amendmentId).then(response => {
                    this.selectedAmendment = response;
                    this.$refs.AmendmentsDetails.show();
                });
            },
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

    .option-tag {
      font-weight: bold;
      padding: .25rem .5rem;
      background: $gray-200;
      border-radius: .5rem;
      text-align: center;

      &.option-tag-1 {
        background: lighten($green, 40%);
        color: darken($green, 20%);
      }
      
      &.option-tag-2 {
        background: lighten($red, 35%);
        color: darken($red, 20%);
      }
      
      &.option-tag-3 {
        background: lighten($orange, 40%);
        color: darken($orange, 20%);
      }
      
      &.option-tag-4 {
        background: lighten($blue, 40%);
        color: darken($blue, 20%);
      }
      
      &.option-tag-5 {
        background: $gray-400;
        color: $gray-800;
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