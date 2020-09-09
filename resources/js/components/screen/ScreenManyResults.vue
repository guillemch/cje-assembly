<template>
    <div class="screen-multiple-results">
        <div :class="['screen-slide', {'screen-slide--current': currentSlide === 0 }]">
            <screen-many-results-summary :amendments="amendments" class="summary" />
        </div>
        <div v-for="(amendment, slide) in amendments" :key="amendment.id" :class="['screen-slide', {'screen-slide--current': currentSlide === slide + 1 }]">
            <div class="vote-name">
                <h1>{{ amendment.name }}</h1>
            </div>
            <div class="row">
                <div class="col-7">
                    <screen-results :amendment="amendment" />
                </div>
                <div class="col-5">
                    <screen-results-by-group :results="amendment.results.by_group" :compensate="amendment.results.compensate" />
                </div>
            </div>
        </div>
        <div class="slide-nav">
            <ul>
                <li :class="['slide-nav-item', {'slide-nav-item--current': currentSlide === 0 }]"></li>
                <li v-for="(amendment, slide) in amendments" :key="`amendment-${amendment.id}`" :class="['slide-nav-item', {'slide-nav-item--current': currentSlide === slide + 1 }]"></li>
            </ul>
        </div>
    </div>
</template>

<script>
    import ScreenManyResultsSummary from './ScreenManyResultsSummary';
    import ScreenResults from './ScreenResults';
    import ScreenResultsByGroup from './ScreenResultsByGroup';

    export default {
        name: 'screen-multiple-results',

        components: {
          ScreenManyResultsSummary,
          ScreenResults,
          ScreenResultsByGroup
        },

        props: {
            amendments: Array
        },

        data () {
            return {
                currentSlide: 0,
                counter: null
            }
        },

        computed: {
            slides () {
                return this.amendments.length;
            }
        },

        mounted () {
            this.nextSlide();
        },

        methods: {
            nextSlide () {
                setTimeout(() => {
                    if (this.currentSlide === this.slides) {
                        this.currentSlide = 0;
                    } else {
                        this.currentSlide++;
                    }

                    this.nextSlide();
                }, 10000);
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import '../../../sass/variables';
    @import '~bootstrap/scss/functions';
    @import '~bootstrap/scss/variables';
    @import '~bootstrap/scss/mixins';

    .screen-multiple-results {
        position: relative;

        .screen-slide {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            width: 100%;
            opacity: 0;
            transition: opacity .5s ease, transform 1s ease;
            transform: translateY(2rem);
            will-change: opacity, transform;

            &--current {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .vote-name {
            h1 {
                font-size: 4vw;
                font-weight: bold;
                padding-right: 18vw;
            }
        }

        .slide-nav {
            position: fixed;
            left: 8vw;
            bottom: 12vw;

            ul {
                display: flex;
                list-style: none;
                margin: 0;
                padding: 0;
            }

            &-item {
                position: relative;
                overflow: hidden;
                border-radius: 50%;
                margin-right: 1rem;
                background: $gray-200;
                padding: .25rem .5rem;
                font-size: .85rem;
                text-align: center;
                width: 1rem;
                height: 1rem;

                &--current {
                    background: $gray-500;
                }
            }
        }
    }
</style>