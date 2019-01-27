<template>
    <div class="amendment-results row">
        <div class="col-md-6">
            <table class="table table-groups table-sm">
                <tr v-for="(group, id) in amendment.results.by_group" :key="'group' + id">
                    <td width="50%">{{ group.acronym }}</td>
                    <td width="50%">
                        <ul class="group-votes">
                            <li v-for="(votes, vote_for) in group.votes" :key="'group' + id + vote_for">
                                <span v-if="votes > 0" :class="'option option-' + vote_for">{{ votes }}</span>
                            </li>
                        </ul>
                    </td>
                </tr>
            </table>
        </div>
        <div class="col-md-6">
            <table class="table table-results">
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
    </div>
</template>

<script>
    export default {
        name: 'amendments-results',

        props: {
          amendment: Object
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
        margin: 0;
        padding: 0;
        list-style: none;

        li {
            display: inline-block;

            .option {
                background: $gray-200;
                color: $white;
                border-radius: 10px;
                padding: .25rem .5rem;
                font-size: .8rem;
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