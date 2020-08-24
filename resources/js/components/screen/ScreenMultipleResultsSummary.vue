<template>
    <table class="table table-multiple-results">
        <tr>
            <th></th>
            <th>A favor</th>
            <th>En contra</th>
            <th>Abstención</th>
        </tr>
        <tr v-for="amendment in amendments" :key="amendment.id" :class="amendment.results.winner">
            <td class="amendment-name" width="30%">{{ amendment.name }}</td>
            <template v-for="option in 3">
                <td v-if="amendment[`option_${option}`]" :key="option" class="option-row" width="20%">
                    <div :class="['option', 'option_' + option, { 'winner': amendment.results.winner === option }]">
                        <span class="option-votes option-votes--c">{{ amendment.results.absolute[1][option] }} <em>C</em></span>
                        <span class="option-votes option-votes--e">{{ amendment.results.absolute[2][option] }} <em>E</em></span>
                        <span class="option-percentage">{{ amendment.results.weighted[option] | percentage }}</span>
                        <i class="option-tick far fa-check" v-if="amendment.results.winner === option" />
                    </div>
                </td>
            </template>
        </tr>
    </table>
</template>

<script>
    export default {
        name: 'screen-multiple-results-summary',

        props: {
            amendments: Array
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

    .table-multiple-results {
      th {
        border-top: 0;
        color: $gray-600;
        font-weight: normal;
        text-align: right;
      }

      .option {
        display: grid;
        grid-template-columns: 2rem 1fr 1fr;
        grid-template-rows: auto auto;
        grid-template-areas:
          "tick percentage percentage"
          "tick votes-c votes-e";
        gap: .4rem;
        padding: .7rem;
        border-radius: .25rem;
        line-height: 1;

        &-percentage {
          grid-area: percentage;
          font-size: 2.25rem;
          text-align: right;
        }

        &-votes {
          text-align: right;
          opacity: .7;

          em {
            font-style: normal;
            opacity: .7;
          }
        }

        &-votes--c {
          grid-area: votes-c;
        }

        &-votes--e {
          grid-area: votes-e
        }

        &-tick {
          grid-area: tick;
          font-size: 2rem;
          align-self: center;
        }

        &-row {
          padding: .25rem;
        }
      }

      .amendment-name {
        font-size: 1.75rem;
        vertical-align: middle;
      }
      
      @each $name, $color in $colors {
        .#{$name}.winner {
          background: $color;
          color: $white;
        }
      }
    }
</style>