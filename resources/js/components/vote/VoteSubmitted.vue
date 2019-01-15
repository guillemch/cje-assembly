<template>
    <div>
        Vote submitted {{ selectedOption }}
    </div>
</template>

<script>
    export default {
        name: 'vote-submitted',

        props: {
            vote: Object
        },

        computed: {
            selectedOption: function () {
                return this.vote['option_' + this.vote.votes[0].vote_for];
            },
        }
    }
</script>