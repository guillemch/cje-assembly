<template>
    <div class="screen-results">
        <table class="table table-results">
            <tr>
                <th></th>
                <th class="text-right">C</th>
                <th class="text-right">E</th>
                <th colspan="2"></th>
            </tr>
            <tr v-for="option in [1, 2, 3, 4, 5]" :key="'option' + option" :class="['option_' + option, (amendment.results.winner === option) ? 'winner' : '']">
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
</template>

<script>
    export default {
        name: 'screen-results',

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
    @import '../../../sass/variables';
    @import '~bootstrap/scss/functions';
    @import '~bootstrap/scss/variables';
    @import '~bootstrap/scss/mixins';

    .table {
        margin-bottom: 0;

        th {
            padding: .5vw 1vw;
            font-size: 1.5vw;
            color: $gray-600;
            border-top: 0;
            font-weight: normal;
        }

        td {
            font-size: 2.75vw;
            padding: 1vw;
        }

        &-sm th {
            padding: .25rem .3rem;
        }
    }

    .table-results {
        .winner {
            td {
                font-weight: bold;
                color: $white;
            }

            @each $name, $color in $colors {
                &.#{$name} td {
                    background: $color;
                }
            }
        }
    }
</style>