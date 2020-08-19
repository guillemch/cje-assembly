<template>
    <div class="vote-open">
        <div v-if="!votes[0].votes.length">
            <ul aria-label="Listado de votaciones" class="vote-list">
                <li v-for="vote in votes" :key="vote.id">
                    <vote-options
                        v-if="selected[vote.id]"
                        :vote="vote"
                        :selected="selected[vote.id]"
                        @select="(option, votes) => updateSelection(vote.id, option, votes)" />
                    <hr />
                </li>
            </ul>
            <b-button v-b-modal.voteConfirm>Vota</b-button>
            <vote-confirm :votes="votes" :selected="selected" @submitted="voteSubmitted" />
        </div>
        <div v-else>
            <vote-submitted :votes="votes" />
        </div>
    </div>
</template>

<script>
    import VoteOptions from './VoteOptions';
    import VoteConfirm from './VoteConfirm';
    import VoteSubmitted from './VoteSubmitted';

    export default {
        name: 'vote-open',

        components: {
            VoteOptions,
            VoteConfirm,
            VoteSubmitted
        },

        props: {
            votes: Array
        },

        data () {
            return {
                selected: {}
            };
        },

        mounted () {
            this.votes.forEach(vote => {
                this.$set(this.selected, vote.id, {
                    option_1: 0,
                    option_2: 0,
                    option_3: 0,
                    option_4: 0,
                    option_5: 0
                });
            });
        },

        methods: {
            voteSubmitted () {
                this.$emit('refresh');
            },

            updateSelection (id, option, votes) {
                this.selected[id][`option_${option}`] = votes;
            }
        }
    }
</script>

<style lang="scss" scoped>
    h2 {
        font-size: 3rem;
        margin-bottom: 2rem;
    }

    .vote-list {
        list-style: none;
        margin: 0;
        padding: 0;
    }
</style>
