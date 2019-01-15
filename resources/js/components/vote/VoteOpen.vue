<template>
    <div>
        <h2>{{ vote.name }}</h2>

        <div v-if="!vote.submitted">
            <vote-options :vote="vote" @selected="selectOption" />
        </div>
        <div v-else>
            Tu voto se ha emitido
        </div>
        <vote-confirm :vote="vote" :selected="selected" @submit="submitVote" />
    </div>
</template>

<script>
    import VoteOptions from './VoteOptions';
    import VoteConfirm from './VoteConfirm';

    export default {
        name: 'vote-open',

        components: {
            VoteOptions,
            VoteConfirm
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

            submitVote (password) {
                alert(password);
            }
        }
    }
</script>