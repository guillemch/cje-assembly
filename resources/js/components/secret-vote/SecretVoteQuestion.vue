<template>
  <div>
    <h3>{{ vote.name }}</h3>
    <div v-if="vote.roll.length > 0">
      Ya has votado
    </div>
    <div v-else-if="vote.open">
      <p>{{ vote.description }}</p>
      <secret-vote-options-single
        v-if="userVotes === 1"
        :options="vote.options"
        :selected="selected"
        @select="(option) => this.updateSelection(option, 1, true)" />
      <secret-vote-options-multiple
        v-else
        :options="vote.options"
        :selected="selected"
        @select="(option, votes) => this.updateSelection(option, votes, false)" />
    </div>
    <div v-else>
      Votación cerrada
    </div>
  </div>
</template>

<script>
  import SecretVoteOptionsSingle from './SecretVoteOptionsSingle.vue';
  import SecretVoteOptionsMultiple from './SecretVoteOptionsMultiple.vue';

  export default {
    name: 'secret-vote-question',

    components: {
      SecretVoteOptionsSingle,
      SecretVoteOptionsMultiple
    },

    props: {
      vote: Object,
      selected: Object
    },

    data () {
      return {
        userVotes: window.user.votes
      }
    },

    methods: {
      updateSelection (option, votes, reset) {
        this.$emit('select', option, votes, reset);
      }
    }
  }
</script>

<style lang="scss" scoped>

</style>