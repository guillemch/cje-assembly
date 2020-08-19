<template>
    <div class="vote__multiple">
        <div class="alert alert-info" v-if="remainingVotes > 0">
            ℹ️ Te quedan <strong>{{ remainingVotes }} votos</strong> por asignar
        </div>
        <div class="alert alert-success" v-else-if="remainingVotes === 0">
            👍 Has asignado <strong>todos los votos</strong>.
        </div>
        <div class="alert alert-danger" v-else>
            ⚠️ Has superado el límite por <strong>{{ Math.abs(remainingVotes) }} voto(s)</strong>.
        </div>
        <ul>
            <template v-for="i in 5">
                <li v-if="vote[`option_${i}`]" :key="i" :class="`slider-${colors[i - 1]}`">
                    <div class="vote__multiple__label">
                        <label>{{ vote[`option_${i}`]}}</label>
                        <span>{{ selected[`option_${i}`] }} votos</span>
                    </div>
                    <vue-slider
                        :value="selected[`option_${i}`]"
                        :dot-size="25"
                        :height="15"
                        :max="userVotes"
                        :drag-on-click="true"
                        :duration="0.2"
                        @change="(value) => $emit('select', i, value)" />
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
                colors: ['success', 'danger', 'warning', 'info', 'secondary']
            }
        },

        computed: {
            totalVotes () {
                return Object.values(this.selected).reduce((a, b) => a + b)
            },
            remainingVotes () {
                return this.userVotes - this.totalVotes
            }
        }
    }
</script>

<style lang="scss">
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
        }
    }

    $colors: ('success': $green, 'danger': $red, 'warning': $orange, 'info': $cyan, 'secondary': $purple);

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
