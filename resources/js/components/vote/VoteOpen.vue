<template>
    <div>
        <h2>{{ vote.name }}</h2>

        <vote-options :vote="vote" @selected="selectOption" v-if="!vote.votes.length > 0" />
        <vote-submitted :vote="vote" v-else />

        <vote-confirm :vote="vote" :selected="selected" @submitted="voteSubmitted" />
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
            vote: Object
        },

        data () {
            return {
                selected: null
            };
        },

        methods: {
            selectOption (option) {
                this.selected = option;
                this.$root.$emit('bv::show::modal', 'voteConfirm', '#option' + option);
            },

            voteSubmitted () {
                this.$emit('refresh');
            }
        }
    }
</script>

<style lang="scss" scoped>
    h2 {
        font-size: 3rem;
        margin-bottom: 2rem;
    }
</style>
