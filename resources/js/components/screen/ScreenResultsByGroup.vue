<template>
    <div class="screen-results-by-group">
        <div class="row">
            <div v-for="group in groups" :key="group.title" class="col-6">
                <table class="table table-groups table-sm">
                    <tr>
                        <th colspan="2">{{ group.title }} <span v-if="compensate == group.type" class="compensated">(voto compensado)</span></th>
                    </tr>
                    <tr>
                        <td>
                            <div class="group-votes">
                                <div v-for="(entity, id) in group.groups" :key="'group' + id" class="group">
                                    <span class="group-acronym">{{ entity.acronym }}</span>
                                    <span class="group-dots">
                                        <ul>
                                            <li v-for="(votes, vote_for) in entity.votes" :key="'group' + id + vote_for">
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
        </div>
    </div>
</template>

<script>
    export default {
        name: 'screen-results-by-group',

        props: {
            results: Object,
            compensate: [Number, Boolean]
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
                        groups: this.sortGroup('1', this.results)
                    },
                    {
                        type: 2,
                        title: 'Entidades',
                        groups: this.sortGroup('2', this.results)
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
        font-size: 1.5vw;

        ul {
            display: inline;
            margin: 0;
            padding: 0;
            margin-left: 5px;
        }

        li {
            display: inline-block;

            .option {
                background: $gray-200;
                color: $white;
                border-radius: 3vw;
                padding: .25vw .75vw;
                font-size: 1.25vw;
                margin-right: .25vw;

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

    .compensated {
        float: right;
    }

</style>
