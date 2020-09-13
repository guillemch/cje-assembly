<template>
    <div class="amendment-results row" v-if="amendment">
        <div class="col-12">
            <h4 class="mb-4">
                {{ amendment.name }}
                <span class="float-right">
                    <i class="far fa-hand-paper" aria-label="Votos recibidos" />
                    {{ amendment.results.total }}
                    <span class="text-muted unique-votes ml-2" v-if="uniqueVoters">
                        <i class="far fa-user-friends" aria-label="Votantes únicos" />
                        {{ amendment.results.unique }}
                    </span>
                </span>
            </h4>
        </div>
        <div class="col-md-6" style="order: 2">
            <table class="table table-results" arial-label="Resultado general">
                <tr>
                    <th><span class="sr-only">Opción</span></th>
                    <th class="text-right" aria-label="Votos de consejos">C</th>
                    <th class="text-right" aria-label="Votos de entidades">E</th>
                    <th><span class="sr-only">Porcentaje</span></th>
                </tr>
                <tr v-for="option in [1, 2, 3, 4, 5]" :key="'option' + option" :class="['option_' + option, (amendment.results.winner === option) ? 'winner' : '']">
                    <template v-if="amendment['option_' + option]">
                        <td width="40%">{{ amendment['option_' + option] }}</td>
                        <td width="15%" class="text-right">{{ amendment.results.absolute[1][option] }}</td>
                        <td width="15%" class="text-right">{{ amendment.results.absolute[2][option] }}</td>
                        <td width="20%" class="text-right">{{ amendment.results.weighted[option] | percentage }}</td>
                        <td width="10%"><i class="far fa-check" v-if="amendment.results.winner === option" aria-label="Opción ganadora" /></td>
                    </template>
                </tr>
            </table>
        </div>
        <div class="col-md-6" style="order: 1">
            <table v-for="group in groups" :key="group.title" class="table table-groups table-sm" :aria-label="`Resultados por ${group.title}`">
                <thead>
                    <tr>
                        <th>{{ group.title }} <span v-if="amendment.results.compensate == group.type">(Voto compensado)</span></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <ul class="group-votes m-0 p-0">
                                <li v-for="(entity, id) in group.groups" :key="'group' + id" class="group">
                                    <span class="group-acronym">{{ entity.acronym }}</span>
                                    <span class="group-votes">
                                        <span v-if="Object.values(entity.votes).reduce((a, b) => a + b) === 0" class="sr-only">Cero votos</span>
                                        <ul v-else class="group-votes">
                                            <template v-for="(votes, vote_for) in entity.votes">
                                                <li v-if="votes > 0" :key="'group' + id + vote_for">
                                                    <span v-if="votes > 0" :class="'option option-fill option_' + vote_for">{{ votes }} <span class="sr-only">votos {{ amendment['option_' + vote_for] }}</span></span>
                                                </li>
                                            </template>
                                        </ul>
                                    </span>
                                </li>
                            </ul>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-12" v-if="fullList" style="order: 3">
            <div v-if="amendment.votes.length > 0">
                <h5>Votos</h5>

                <table class="table table-sm table-striped">
                    <tr v-for="vote in amendment.votes" :key="vote.id">
                        <td>
                            {{ vote.type == 1 ? 'C' : 'E' }}
                        </td>
                        <td>{{ vote.acronym }}</td>
                        <td>{{ vote.last_name + ', ' + vote.name }}</td>
                        <td class="text-right">
                            {{ amendment['option_' + vote.vote_for] }}
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'amendments-results',

        props: {
          amendment: Object,
          fullList: Boolean,
          uniqueVoters: Boolean
        },

        filters: {
            percentage: function (value) {
                return String(value).replace('.', ',') + '%';
            }
        },

        computed: {
            groups: function () {
                return [
                    {
                        type: 1,
                        title: 'Consejos',
                        groups: this.sortGroup('1', this.amendment.results.by_group)
                    },
                    {
                        type: 2,
                        title: 'Entidades',
                        groups: this.sortGroup('2', this.amendment.results.by_group)
                    }
                ];
            }
        },

        methods: {
            sortGroup (type, groups) {
                const filteredGroups = Object.values(groups).filter(group => group.type === type);
                const sortedGroups = filteredGroups.sort((a, b) => (a.acronym > b.acronym) ? 1 : ((b.acronym > a.acronym) ? -1 : 0));

                return sortedGroups;
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import '../../../sass/variables';
    @import '~bootstrap/scss/functions';
    @import '~bootstrap/scss/variables';
    @import '~bootstrap/scss/mixins';

    .table {
        th {
            padding: .25rem .75rem;
            font-size: .8rem;
            color: $gray-600;
            border-top: 0;
            font-weight: normal;
        }

        &-sm th {
            padding: .25rem .3rem;
        }
    }

    .table-results {
        .winner {
            td {
                font-weight: bold;
            }

            @each $name, $color in $colors {
                &.#{$name} td {
                    background: mix($color, $white, 15%);
                    color: darken($color, 15%);
                }
            }
        }
    }
    .group-votes {
        display: flex;
        flex-wrap: wrap;
        align-content: stretch;
    }

    .group {
        display: inline-block;
        background: $gray-200;
        margin: 0 .5rem .5rem 0;
        padding: .3rem .5rem;
        list-style: none;
        border-radius: .25rem;
        font-size: .9rem;

        ul {
            margin: 0;
        }

        li {
            display: inline-block;

            .option {
                color: $white;
                border-radius: 10px;
                padding: .25rem .5rem;
                font-size: .7rem;
                margin-right: .25rem;
            }
        }
    }

    .unique-votes {
        font-weight: 400;
    }
</style>
