<template>
    <div class="vote__single">
        <b-form-group label="Opciones" label-sr-only>
            <template v-for="i in 5">
                <b-form-radio
                    v-if="vote[`option_${i}`]"
                    :key="i"
                    :checked="selected[`option_${i}`] > 0"
                    :name="`options[${vote.id}]`"
                    :value="i"
                    @change="$emit('select', i)"
                    :class="[`radio-option_${i}`, {'selected': selected[`option_${i}`] > 0}]">
                    {{ vote[`option_${i}`] }}
                </b-form-radio>
            </template>
        </b-form-group>
    </div>
</template>

<script>
    export default {
        name: 'vote-options-single',

        props: {
            vote: Object,
            selected: Object
        },

        data () {
            return {
                colors: ['success', 'danger', 'warning', 'info', 'secondary']
            }
        },
    }
</script>

<style lang="scss">
    @import '../../../sass/variables';
    @import '~bootstrap/scss/functions';
    @import '~bootstrap/scss/variables';
    @import '~bootstrap/scss/mixins';

    .vote__single {
        .custom-radio {
            display: block;
            border: 2px solid $gray-500;
            border-radius: $border-radius-lg;
            margin: .75rem 0;
            color: $gray-800;
            padding-left: 2.5rem;
            transition: .2s ease;

            label {
                display: block;
                font-weight: 500;
                padding: 1.25rem .5rem;
            }

            &:hover {
                background: $gray-200;
                border-color: $gray-600;
            }

            &.selected {
                background: $gray-500;
                color: $white;
                border-color: $gray-600;
            }

            .custom-control-label::before,
            .custom-control-label::after {
                top: 1.5rem;
            }
        }

        @each $name, $color in $colors {
            .radio-#{$name} {
                border-color: $color;
                background: mix($color, $white, 10%);
                color: darken($color, 25%);

                &:hover {
                    background: mix($color, $white, 25%);
                    border-color: darken($color, 10%);
                }

                &.selected {
                    background: $color;
                    border-color: darken($color, 15%);
                }

                .custom-control-input:checked ~ .custom-control-label::before {
                    background-color: darken($color, 20%);
                    border-color: darken($color, 20%);
                }

                .custom-control-input:not(:disabled):active ~ .custom-control-label::before {
                    background-color: mix($color, $white, 50%);
                    border-color: mix($color, $white, 50%);
                }

                .custom-control-input:focus:not(:checked) ~ .custom-control-label::before {
                    border-color: darken($color, 20%);
                }

                .custom-control-input:focus ~ .custom-control-label::before {
                    box-shadow: 0 0 0 0.2rem rgba($color, .25);
                }
            }
        }
    }
</style>