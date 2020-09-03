<template>
    <ul :class="['vote__summary', {'vote__summary--multiple': userVotes > 1}]">
        <li v-for="vote in votes" :key="vote.id">
            <span class="vote__summary__name">{{ vote.name }}</span>
            <span class="vote__summary__divider"></span>
            <div v-if="Object.values(selected[vote.id]).some((votes) => votes > 0)" class="vote__summary__selection">
                <div v-if="Object.values(selected[vote.id]).reduce((a, b) => a + b) < userVotes">
                    <div class="alert alert-sm alert-info">
                        ℹ️ No has asignado todos tus votos
                    </div>
                </div>
                <div v-else-if="Object.values(selected[vote.id]).reduce((a, b) => a + b) > userVotes">
                    <div class="alert alert-sm alert-danger">
                        🛑 Has asignado más votos de los disponibles
                    </div>
                </div>
                <ul>
                    <template v-for="(votes, key) in selected[vote.id]">
                        <li v-if="(userVotes > 1 && vote[key]) || (userVotes === 1 && votes === 1)" :key="key">
                            <span v-if="userVotes > 1">
                                <span :class="['option-text', key]">{{ vote[key] }}</span>
                                <span>× {{ votes }}</span>
                            </span>
                            <span v-else :class="['vote-pill option-fill', key]">
                                {{ vote[key] }}
                            </span>
                        </li>
                    </template>
                </ul>
            </div>
            <span v-else class="vote__summary__ignore">No votar</span>
        </li>
    </ul>
</template>

<script>
    export default {
        name: 'vote-summary',

        props: {
            votes: Array,
            selected: Object
        },

        data () {
            return {
                userVotes: window.user.votes
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import '../../../sass/variables';
    @import '~bootstrap/scss/functions';
    @import '~bootstrap/scss/variables';
    @import '~bootstrap/scss/mixins';

    .vote__summary {
        margin: 0;
        padding: 0;
        list-style: none;

        & > li {
            display: flex;
            margin: 1rem 0;
        }

        &__divider {
            border-bottom: 1px dotted $gray-500;
            flex-grow: 1;
            flex-shrink: 0;
            position: relative;
            top: -5px;
            margin: 0 .5rem;
        }

        &__selection ul {
            margin: 0;
            padding: 0;
            list-style: none;
        }

        &__ignore {
            color: $gray-600;
            font-style: italic;
        }

        &__name {
            display: block;
            word-wrap: break-word;
            overflow-wrap: break-word;
            max-width: 50%;
        }

        &--multiple {
            & > li {
                flex-direction: column;
            }

            .vote__summary__divider {
                display: none;
            }

            .vote__summary__name {
                font-weight: bold;
                border-bottom: 1px dotted $gray-500;
                margin-bottom: .5rem;
                max-width: 100%;
            }
        }

        .vote-pill {
            color: $white;
            padding: .25rem .75rem;
            border-radius: $border-radius-lg;
            white-space: nowrap;
        }
    }
</style>
