<template>
    <div class="vote__multiple">
        <div class="alert alert-info" v-if="remainingVotes > 0" aria-live="assertive" role="alert">
            <span aria-hidden="true">ℹ️</span> Te quedan <strong>{{ remainingVotes }} votos</strong> por asignar
        </div>
        <div class="alert alert-success" v-else-if="remainingVotes === 0" aria-live="assertive" role="alert">
            <span aria-hidden="true">👍</span> Has asignado <strong>todos los votos</strong>.
        </div>
        <div class="alert alert-danger" v-else aria-live="assertive" role="alert">
            <span aria-hidden="true">🛑</span> Has superado el límite por <strong>{{ Math.abs(remainingVotes) }} voto(s)</strong>.
        </div>
        <ul>
            <template v-for="i in 5">
                <li v-if="vote[`option_${i}`]" :key="i" :class="`slider-option_${i}`">
                    <div class="vote__multiple__label">
                        <label :for="`votes${vote.id}${i}`">{{ vote[`option_${i}`]}}</label>
                        <span>
                            <input
                                :id="`votes${vote.id}${i}`"
                                type="number"
                                :value="selected[`option_${i}`]"
                                class="vote__multiple__number"
                                min="0"
                                :max="userVotes"
                                :aria-description="`Número de votos ${vote[`option_${i}`]}`"
                                @input="updateVoteNumber(i, $event)">
                            <span aria-hidden="true">votos</span>
                        </span>
                    </div>
                    <div aria-hidden="true">
                        <vue-slider
                            :value="selected[`option_${i}`]"
                            :dot-size="25"
                            :height="15"
                            :max="userVotes"
                            :drag-on-click="true"
                            :duration="0.2"
                            @change="(value) => $emit('select', i, value)" />
                    </div>
                </li>
            </template>
        </ul>
    </div>
</template>

<script>
    import VueSlider from 'vue-slider-component'
    import 'vue-slider-component/theme/antd.css'

    export default {
        name: 'vote-options-multple',

        components: {
            VueSlider
        },

        props: {
            vote: Object,
            selected: Object,
        },

        data () {
            return {
                userVotes: window.user.votes,
            }
        },

        computed: {
            totalVotes () {
                return Object.values(this.selected).reduce((a, b) => a + b)
            },
            remainingVotes () {
                return this.userVotes - this.totalVotes
            }
        },

        methods: {
            updateVoteNumber (i, e) {
                let number = e.target.value

                if (typeof number !== 'number') {
                    this.$emit('select', i, 0)
                } else {
                    this.$emit('select', i, parseInt(e.target.value))
                }
            }
        }
    }
</script>

<style lang="scss">
    @import '../../../sass/variables';
    @import '~bootstrap/scss/functions';
    @import '~bootstrap/scss/variables';
    @import '~bootstrap/scss/mixins';

    .vote {
        &__multiple {
            ul {
                list-style: none;
                margin: 0;
                padding: 0;
            }

            li {
                margin: 2rem 0;
            }

            &__label {
                display: flex;

                label {
                    margin: 0;
                }

                span {
                    margin-left: auto;
                }
            }

            &__number {
                background: white;
                text-align: right;
                border: 1px solid $gray-400;
                padding-right: .15rem;
                border-radius: 4px;
                width: 2.75rem;
            }

            .alert {
                white-space: nowrap;
                overflow-x: auto;
                padding: .5rem;

                @include media-breakpoint-up(lg) {
                    padding: .5rem 1rem;
                }
            }
        }
    }

    @each $name, $color in $colors {
        .slider {
            &-#{$name} {
                .vue-slider-dot-handle-focus {
                    border-color: $color;
                    box-shadow: 0 0 0 5px rgba($color, 0.2);
                }

                .vue-slider-dot-handle {
                    border-color: $color;
                }

                .vue-slider-process,
                .vue-slider:hover .vue-slider-process {
                    background-color: $color;
                }

                .vue-slider:hover .vue-slider-dot-handle-focus,
                .vue-slider:hover .vue-slider-dot-handle {
                    border-color: $color;
                }

                .vue-slider-rail,
                .vue-slider:hover .vue-slider-rail {
                    background-color: mix($color, white, 10%);
                    box-shadow: inset 0 0 0 1px mix($color, white, 40%);
                }
            }
        }
    }
</style>
