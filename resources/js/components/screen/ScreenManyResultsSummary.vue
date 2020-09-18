<template>
    <div>
      <h3>{{ amendments[0].description }}</h3>
      <ul class="list-many-results">
          <li v-for="amendment in amendments" :key="amendment.id" :class="`winner-option_${amendment.results.winner}`">
              <span class="vote-name">{{ amendment.name }}</span>
              <span class="vote-result">{{ options[amendment.results.winner] }}</span>
          </li>
      </ul>
    </div>
</template>

<script>
    export default {
        name: 'screen-many-results-summary',

        props: {
            amendments: Array
        },

        data () {
          return {
            options: ['--', 'Sí', 'No']
          }
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

    h3 {
        font-size: 3vw;
        font-weight: bold;
        padding-right: 18vw;
        transition: 1s ease;
        margin-bottom: 1rem;
    }

    .list-many-results {
      display: grid;
      grid-template-columns: repeat(4, 1fr);
      gap: 1.25vw;
      list-style: none;
      margin: 0;
      padding: 0;

      li {
        display: flex;
        font-size: 1.4vw;
        border: 2px $gray-400 solid;
        border-radius: 1vw;
        overflow: hidden;

        .vote-name {
          padding: 1vw;
          white-space: nowrap;
          overflow: hidden;
          text-overflow: ellipsis;
        }

        .vote-result {
          padding: 1vw;
          color: $white;
          margin-left: auto;
          width: 5vw;
          text-align: center;
        }
      }
      
      @each $name, $color in $colors {
        .winner-#{$name} {
          border-color: $color;
          background: rgba($color, .1);

          .vote-result {
            background: $color;
          }
        }
      }
    }
</style>