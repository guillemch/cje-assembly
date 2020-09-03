<template>
  <div>
    <h3>{{ vote.name }}</h3>
    <div v-if="vote.roll.length > 0" class="vote-status voted">
      Ya has votado
      <i class="far fa-vote-yea"></i>
    </div>
    <div v-else-if="vote.open">
      <p class="text-muted">{{ vote.description }}</p>
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
    <div v-else class="vote-status closed">
      Votación cerrada
      <i class="far fa-vote-nay"></i>
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
    @import '../../../sass/variables';
    @import '~bootstrap/scss/functions';
    @import '~bootstrap/scss/variables';
    @import '~bootstrap/scss/mixins';

    .vote-status {
      display: flex;
      font-size: 1.5rem;
      padding: 1rem;
      border-radius: .5rem;
      border: 2px solid $gray-500;
      align-items: center;
      background: $gray-200;
      color: $gray-700;

      &.voted {
        color: darken($green, 10%);
        background: rgba($green, .2);
        border-color: $green;
      }

      .far {
        margin-left: auto;
      }
    }
</style>
