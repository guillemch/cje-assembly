<template>
  <div v-if="Object.keys(selected).length && secretVotes.length" class="secret-votes">
    <ul>
      <li v-for="vote in secretVotes" :key="vote.id">
        <secret-vote-question
          :vote="vote"
          :selected="selected[vote.id]"
          @select="(option, votes, reset) => updateSelection(vote.id, option, votes, reset)" />
      </li>
    </ul>
    <div class="vote-button" v-if="secretVotes.some(vote => vote.open === 1 && vote.roll.length === 0)">
      <div
        class="vote-button__wrapper"
        v-tooltip="!canVote ? 'Hay algunos errores en los votos asignados' : false">
        <b-button
          size="lg"
          variant="primary"
          block
          :class="!canVote ? 'disabled' : ''"
          v-b-modal.voteConfirm>
          🗳 &nbsp; Revisa y vota
        </b-button>
      </div>
    </div>
    <secret-vote-confirm
      :votes="secretVotes"
      :selected="selected"
      :can-vote="canVote"
      @submitted="getSecretVotes" />
  </div>
</template>

<script>
  import SecretVoteQuestion from './SecretVoteQuestion.vue';
  import SecretVoteConfirm from './SecretVoteConfirm.vue';

  export default {
    name: 'secret-vote',

    components: {
      SecretVoteQuestion,
      SecretVoteConfirm
    },

    data () {
      return {
        secretVotes: [],
        selected: {},
        userVotes: window.user.votes
      }
    },

    mounted () {
      this.getSecretVotes();
    },

    computed: {
      canVote () {
        if (!Object.keys(this.selected).length) return false;

        // Sum every vote
        const sumVotes = [];
        Object.keys(this.selected).forEach(voteId => {
            sumVotes.push(Object.values(this.selected[voteId]).reduce((a, b) => a + b));
        });

        // At least one vote in one question
        if (sumVotes.reduce((a, b) => a + b) === 0) return false;

        // If multiple, not over limit
        if (!sumVotes.every((value) => value <= this.userVotes)) return false;

        return true;
      }
    },

    methods: {
      getSecretVotes() {
        API.getSecretVotes().then(result => {
          this.secretVotes = result;
          this.resetSelection();
        }).catch(() => {
          alert('Error al cargar los votos');
        })
      },

      updateSelection (id, option, votes, reset) {
        if (reset) {
          const optionsObject = {};
          Object.keys(this.selected[id]).forEach(option => {
            optionsObject[option] = 0;
          });
          this.$set(this.selected, id, optionsObject);
        }

        this.selected[id][option] = votes;
      },

      resetSelection () {
        this.secretVotes.forEach(vote => {
          const optionsObject = {};
          vote.options.forEach(option => {
            optionsObject[option.id] = 0;
          });
          this.$set(this.selected, vote.id, optionsObject);
        });
      }
    }
  }
</script>

<style lang="scss" scoped>
  @import '../../../sass/variables';
  @import '~bootstrap/scss/functions';
  @import '~bootstrap/scss/variables';
  @import '~bootstrap/scss/mixins';

  .secret-votes {
    max-width: 450px;
    margin: 0 auto;
  }

  ul {
    margin: 0;
    padding: 0;
    margin-bottom: 4rem;
  }

  li {
    margin: 0;
    padding: 2rem 0;
    list-style: none;
    border-bottom: 1px $gray-200 solid;
  }
</style>
