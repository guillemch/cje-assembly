<template>
  <div class="vote__single">
    <b-form-group label="Opciones" label-sr-only v-if="selected">
      <b-form-radio
        v-for="option in options"
        :key="option.id"
        :checked="selected[option.id] > 0"
        :name="`options[${option.secret_vote_id}]`"
        :value="option.id"
        @change="$emit('select', option.id)"
        :class="{'selected': selected[option.id] > 0}">
        {{ option.name }}
      </b-form-radio>
    </b-form-group>
  </div>
</template>

<script>
  export default {
    name: 'secret-vote-options-single',

    props: {
      options: Array,
      selected: Object
    }
  }
</script>

<style lang="scss">
  @import '../../../sass/variables';
  @import '~bootstrap/scss/functions';
  @import '~bootstrap/scss/variables';
  @import '~bootstrap/scss/mixins';

  .vote__single {
    .custom-radio {
      display: block;
      border: 2px solid mix($primary, $white, 60%);
      background: mix($primary, $white, 10%);
      border-radius: $border-radius-lg;
      margin: .75rem 0;
      color: $primary;
      padding-left: 2.5rem;
      transition: .2s ease;

      label {
        display: block;
        font-weight: 500;
        padding: 1.25rem .5rem;
      }

      &:hover {
        background: mix($primary, $white, 20%);
        border-color: mix($primary, $white, 70%);
      }

      &:focus-within {
          box-shadow: 0 0 0 .25rem rgba($cje-yellow, .5);
      }

      &.selected {
        background: $cje-yellow;
        color: $primary;
        border-color: darken($cje-yellow, 30%);
      }

      .custom-control-label::before,
      .custom-control-label::after {
        top: 1.5rem;
      }
    }
  }
</style>
