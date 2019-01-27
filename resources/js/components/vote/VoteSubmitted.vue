<template>
    <div class="placeholder">
        <i class="far fa-check-circle" />
        Voto registrado
        <div :class="'option option-' + vote.votes[0].vote_for">
            {{ selectedOption }}
        </div>
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

<style lang="scss" scoped>
    @import '~bootstrap/scss/functions';
    @import '~bootstrap/scss/variables';
    @import '~bootstrap/scss/mixins';
    
    .placeholder {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: $gray-800;
        font-size: 2rem;
        height: 40vh;
        text-align: center;
        line-height: 1.15;

        .far {
            font-size: 4rem;
            margin-bottom: 2rem;
        }
    }

    .option {
        background: $gray-200;
        color: $white;
        padding: .5rem 2rem;
        border-radius: .5rem;
        font-size: 2.5rem;
        margin-top: 1rem;

        &.option-1 {
            background: $green;
        }

        &.option-2 {
            background: $red;
        }

        &.option-3 {
            background: $orange;
        }

        &.option-4 {
            background: $blue;
        }

        &.option-5 {
            background: $gray-800;
        }
    }

</style>