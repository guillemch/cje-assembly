<template>
    <div class="submitted">
        <div class="submitted__title" aria-live="assertive"> 
            <i class="far fa-check-circle" />
            Voto registrado
        </div>
        <div class="submitted__summary">
            <h2 class="sr-only" id="summary">Resumen del voto</h2>
            <table class="table" aria-labelledby="summary">
                <thead class="sr-only">
                    <tr>
                        <th>Nombre de la votación</th>
                        <th>Opción emitida</th>
                    </tr>
                </thead>
                <tbody>
                    <tr v-for="amendment in votes" :key="amendment.id">
                        <td class="vote-name" width="40%">
                            {{ amendment.name }}
                        </td>
                        <td class="vote-pills" width="60%">
                            <ul v-if="amendment.votes.length > 1">
                                <li
                                    v-for="vote in amendment.votes"
                                    :key="vote.id"
                                    :class="['vote-pill option-fill', `option_${vote.vote_for}`]">
                                    {{ amendment[`option_${vote.vote_for}`] }}
                                </li>
                            </ul>
                            <span v-else :class="['vote-pill option-fill', `option_${amendment.votes[0].vote_for}`]">
                                {{ amendment[`option_${amendment.votes[0].vote_for}`] }}
                            </span>
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

        .vote-name {
            padding-top: 1rem;
        }

        .vote-pills {
            padding-bottom: .25rem;

            ul {
                display: flex;
                flex-wrap: wrap;
                margin: 0;
                padding: 0;
                list-style: none;
                justify-content: flex-end;
            }

            .vote-pill {
                color: $white;
                padding: .25rem .75rem;
                border-radius: $border-radius-lg;
                white-space: nowrap;
                margin: 0 .5rem .5rem 0;
            }
        }
    }
</style>