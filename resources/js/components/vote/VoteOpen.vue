<template>
    <div class="vote-open" v-if="Object.keys(selected).length">
        <div v-if="!votes[0].votes.length">
            <ul aria-label="Listado de votaciones" class="vote-list">
                <li v-for="vote in votes" :key="vote.id">
                    <vote-options
                        v-if="selected[vote.id]"
                        :vote="vote"
                        :selected="selected[vote.id]"
                        @select="(option, votes, reset) => updateSelection(vote.id, option, votes, reset)" />
                    <hr />
                </li>
            </ul>
            <div class="vote-button">
                <div
                    class="vote-button__wrapper"
                    v-b-tooltip.hover="!canVote ? 'Hay algunos errores en los votos asignados' : false">
                    <b-button
                        size="lg"
                        variant="primary"
                        block
                        :class="!canVote ? 'disabled' : ''"
                        v-b-modal.voteConfirm>
                        ✋&nbsp; Vota
                    </b-button>
                </div>
            </div>
            <vote-confirm :votes="votes" :selected="selected" :can-vote="canVote" @submitted="voteSubmitted" />
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
                selected: {},
                userVotes: window.user.votes
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

        computed: {
            canVote () {
                if (!Object.keys(this.selected).length) return false;

                // Sum every vote
                const sumVotes = [];
                Object.keys(this.selected).forEach(voteId => {
                    sumVotes.push(Object.values(this.selected[voteId]).reduce((a, b) => a + b));
                });

                // At least one vote in one amendment
                if (sumVotes.reduce((a, b) => a + b) === 0) return false;

                // If multiple, not over limit
                if (!sumVotes.every((value) => value <= this.userVotes)) return false;

                return true;
            }
        },

        methods: {
            voteSubmitted () {
                this.$emit('refresh');
            },

            updateSelection (id, option, votes, reset) {
                if (reset) {
                    this.$set(this.selected, id, {
                        option_1: 0,
                        option_2: 0,
                        option_3: 0,
                        option_4: 0,
                        option_5: 0
                    });
                }

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
        margin-bottom: 5rem;

        li:last-child hr {
            display: none;
        }

        hr {
            margin: 2rem 0;
        }
    }
</style>

