<template>
    <div>
        <b-btn @click="getCurrentVote">Refresh</b-btn>
        <div v-if="!vote || vote.length === 0">
            No vote open...
        </div>
        <vote-open :vote="vote" v-else />
    </div>
</template>

<script>
    import VoteOpen from './VoteOpen';

    export default {
        name: 'vote',

        components: {
            VoteOpen
        },

        data () {
            return {
                vote: null,
                loading: false
            };
        },

        mounted () {
            this.getCurrentVote();
        },

        methods: {
            getCurrentVote () {
                this.loading = true;

                API.getCurrentVote().then(response => {
                    this.vote = response[0];
                }).catch(error => {
                    alert('Error');
                }).then(() => this.loading = false);
            }
        }
    }
</script>