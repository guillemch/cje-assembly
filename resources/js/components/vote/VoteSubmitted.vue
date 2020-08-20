<template>
    <div class="submitted">
        <div class="submitted__title">
            <i class="far fa-check-circle" />
            Voto registrado
        </div>
        <div class="submitted__summary">
            <table class="table">
                <tbody>
                    <tr v-for="amendment in votes" :key="amendment.id">
                        <td width="40%">
                            {{ amendment.name }}
                        </td>
                        <td width="60%">
                            <ul class="vote-pills">
                                <li
                                    v-for="vote in amendment.votes"
                                    :key="vote.id"
                                    :class="['vote-pill', `option_${vote.vote_for}`]">
                                    {{ amendment[`option_${vote.vote_for}`] }}
                                </li>
                            </ul>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'vote-submitted',

        props: {
            votes: Array
        }
    }
</script>

<style lang="scss" scoped>
    @import '../../../sass/variables';
    @import '~bootstrap/scss/functions';
    @import '~bootstrap/scss/variables';
    @import '~bootstrap/scss/mixins';
    
    .submitted {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        color: $gray-800;
        min-height: 80vh;
        max-width: 600px;
        margin: 0 auto;
        line-height: 1.15;

        .far {
            font-size: 4rem;
            margin-bottom: 2rem;
        }

        &__title {
            display: flex;
            flex-direction: column;
            font-size: 2rem;
            text-align: center;
        }

        &__summary {
            margin-top: 2rem;
        }

        .vote-pills {
            display: flex;
            flex-wrap: wrap;
            margin: 0;
            padding: 0;
            list-style: none;
            justify-content: flex-end;
        }

        .vote-pill {
            background: $gray-500;
            color: $white;
            padding: .25rem .75rem;
            border-radius: $border-radius-lg;
            white-space: nowrap;
            margin: 0 .5rem .5rem 0;
        }

        @each $name, $color in $colors {
            .#{$name} {
                background: $color;
            }
        }
    }
</style>