<template>
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

<style lang="scss" scoped>

</style>