<template>
    <div class="vote__multiple">
        <div class="alert alert-info" v-if="remainingVotes > 0">
            ℹ️ Te quedan <strong>{{ remainingVotes }} votos</strong> por asignar
        </div>
        <div class="alert alert-success" v-else-if="remainingVotes === 0">
            👍 Has asignado <strong>todos los votos</strong>.
        </div>
        <div class="alert alert-danger" v-else>
            🛑 Has superado el límite por <strong>{{ Math.abs(remainingVotes) }} voto(s)</strong>.
        </div>
        <ul>
            <li v-for="option in options" :key="option.id" class="slider">
                <div class="vote__multiple__label">
                    <label>{{ option.name }}</label>
                    <span>
                        <input
                            type="number"
                            :value="selected[option.id]"
                            class="vote__multiple__number"
                            min="0"
                            :max="userVotes"
                            @input="$emit('select', option.id, parseInt($event.target.value))">
                        votos
                    </span>
                </div>
                <vue-slider
                    :value="selected[option.id]"
                    :dot-size="25"
                    :height="15"
                    :max="userVotes"
                    :drag-on-click="true"
                    :duration="0.2"
                    @change="(value) => $emit('select', option.id, value)" />
            </li>
        </ul>
    </div>
</template>

<script>
    import VueSlider from 'vue-slider-component'
    import 'vue-slider-component/theme/antd.css'

    export default {
        name: 'secret-vote-options-multiple',

        components: {
            VueSlider
        },

        props: {
            options: Array,
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

        .slider {
            .vue-slider-dot-handle-focus {
                border-color: $primary;
                box-shadow: 0 0 0 5px rgba($primary, 0.2);
            }

            .vue-slider-dot-handle {
                border-color: $primary;
            }

            .vue-slider-process,
            .vue-slider:hover .vue-slider-process {
                background-color: $cje-yellow;
            }

            .vue-slider:hover .vue-slider-dot-handle-focus,
            .vue-slider:hover .vue-slider-dot-handle {
                border-color: $primary;
            }

            .vue-slider-rail,
            .vue-slider:hover .vue-slider-rail {
                background-color: mix($primary, white, 10%);
                box-shadow: inset 0 0 0 1px mix($primary, white, 40%);
            }
    }
</style>
