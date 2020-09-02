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
            <li v-for="option in options" :key="option.id">
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

<style lang="scss" scoped>

</style>