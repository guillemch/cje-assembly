<template>
    <div class="screen-results-by-group">
        <table class="table table-groups table-sm">
            <tr>
                <th colspan="2">Entidades</th>
            </tr>
            <tr>
                <td>
                    <div class="group-votes">
                        <div v-for="(group, id) in results" :key="'group' + id" class="group">
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
</template>

<script>
    export default {
        name: 'screen-results-by-group',

        props: {
            results: Object
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

    .screen-results-by-group {
        margin-top: 3vh;
    }

    .table {
        th {
            padding: .5vw 1vw;
            font-size: 1.25vw;
            color: $gray-600;
            border-top: 0;
            font-weight: normal;
        }

        td {
            font-size: 1.25vw;
            padding: 1vw;
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
        margin: 0 .75vw .75vw 0;
        padding: .5vw .75vw;
        list-style: none;
        border-radius: .5vw;
        font-size: 1.25vw;

        ul {
            margin: 0;
        }

        li {
            display: inline-block;

            .option {
                background: $gray-200;
                color: $white;
                border-radius: 3vw;
                padding: .25vw .75vw;
                font-size: 1vw;
                margin-right: .5vw;

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