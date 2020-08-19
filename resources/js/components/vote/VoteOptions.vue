<template>
    <div class="vote__options">
        <h2>{{ vote.name }}</h2>

        <vote-options-multiple
            v-if="userVotes > 1"
            :vote="vote"
            :selected="selected"
            @select="(option, votes) => this.updateSelection(option, votes)" />
        <vote-options-single
            v-else
            :vote="vote"
            :selected="selected"
            @select="(option) => this.updateSelection(option, 1)" />
    </div>
</template>

<script>
    import VoteOptionsMultiple from './VoteOptionsMultiple';
    import VoteOptionsSingle from './VoteOptionsSingle';

    export default {
        name: 'vote-options',

        components: {
            VoteOptionsMultiple,
            VoteOptionsSingle
        },

        data () {
            return {
                userVotes: window.user.votes
            }
        },

        props: {
            vote: Object,
            selected: Object
        },

        methods: {
            updateSelection (option, votes) {
                this.$emit('select', option, votes);
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import '~bootstrap/scss/functions';
    @import '~bootstrap/scss/variables';
    @import '~bootstrap/scss/mixins';

    .vote {
        &__options {
            max-width: 400px;
            margin: 0 auto;

            .btn {
                padding: 1rem 0;
                margin: 1.5rem 0;
                font-size: 1.5rem;
            }
        }
    }
</style>