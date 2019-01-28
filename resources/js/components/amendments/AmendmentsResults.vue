<template>
    <div class="amendment-results row" v-if="amendment">
        <div class="col-12">
            <h4 class="mb-4">
                {{ amendment.name }}
                <span class="float-right">
                    {{ amendment.results.total }}
                </span>    
            </h4>
        </div>
        <div class="col-md-6">
            <table class="table table-groups table-sm">
                <tr>
                    <th colspan="2">Entidades</th>
                </tr>
                <tr>
                    <td>
                        <div class="group-votes">
                            <div v-for="(group, id) in amendment.results.by_group" :key="'group' + id" class="group">
                                <span class="group-acronym">{{ group.acronym }}</span>
                                <span class="group-votes">
                                    <ul class="group-votes">
                                        <li v-for="(votes, vote_for) in group.votes" :key="'group' + id + vote_for">
                                            <span v-if="votes > 0" :class="'option option-' + vote_for">{{ votes }}</span>
                                        </li>
                                    </ul>
                                </span>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div class="col-md-6">
            <table class="table table-results">
                <tr>
                    <th></th>
                    <th class="text-right">A</th>
                    <th class="text-right">B</th>
                    <th colspan="2"></th>
                </tr>
                <tr v-for="option in [1, 2, 3, 4, 5]" :key="'option' + option" :class="['option-' + option, (amendment.results.winner === option) ? 'winner' : '']">
                    <template v-if="amendment['option_' + option]">
                        <td width="40%">{{ amendment['option_' + option] }}</td>
                        <td width="15%" class="text-right">{{ amendment.results.absolute[1][option] }}</td>
                        <td width="15%" class="text-right">{{ amendment.results.absolute[2][option] }}</td>
                        <td width="20%" class="text-right">{{ amendment.results.weighted[option] | percentage }}</td>
                        <td width="10%"><i class="far fa-check" v-if="amendment.results.winner === option" /></td>
                    </template>
                </tr>
            </table>
        </div>
        <div class="col-12" v-if="fullList">
            <div v-if="amendment.votes.length > 0">
                <h5>Votos</h5>

                <table class="table table-sm table-striped">
                    <tr v-for="vote in amendment.votes" :key="vote.id">
                        <td>
                            {{ vote.type == 1 ? 'A' : 'B' }}
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
          fullList: Boolean
        },

        filters: {
            percentage: function (value) {
                return String(value).replace('.', ',') + '%';
            }
        }
    }
</script>

<style lang="scss" scoped>
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

            &.option-1 td {
                background: lighten($green, 40%);
                color: darken($green, 20%);
            }

            &.option-2 td {
                background: lighten($red, 40%);
                color: darken($red, 20%);
            }

            &.option-3 td {
                background: lighten($orange, 40%);
                color: darken($orange, 20%);
            }

            &.option-4 td {
                background: lighten($blue, 40%);
                color: darken($blue, 20%);
            }

            &.option-5 td {
                background: $gray-400;
                color: $gray-800;
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
        background: $gray-100;
        margin: 0 .5rem .5rem 0;
        padding: .5rem;
        list-style: none;
        border-radius: .5rem;
        font-size: .9rem;

        ul {
            margin: 0;
        }

        li {
            display: inline-block;

            .option {
                background: $gray-200;
                color: $white;
                border-radius: 10px;
                padding: .25rem .5rem;
                font-size: .7rem;
                margin-right: .25rem;

                &.option-1 {
                    background: $green;
                }

                &.option-2 {
                    background: $red;
                }

                &.option-3 {
                    background: $orange;
                }

                &.option-4 {
                    background: $blue;
                }

                &.option-5 {
                    background: $black;
                }
            }
        }
    }
</style>