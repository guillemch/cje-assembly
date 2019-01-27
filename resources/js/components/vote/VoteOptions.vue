<template>
    <div class="vote__options">
        <b-btn id="option1" variant="success" :block="true" size="lg" @click="$emit('selected', 1)">
            {{ vote.option_1 }}
        </b-btn>

        <b-btn id="option2" variant="danger" :block="true" size="lg" @click="$emit('selected', 2)">
            {{ vote.option_2 }}
        </b-btn>

        <b-btn id="option3" variant="warning" :block="true" size="lg" v-if="vote.option_3"  @click="$emit('selected', 3)">
            {{ vote.option_3 }}
        </b-btn>

        <b-btn id="option4" variant="info" :block="true" size="lg" v-if="vote.option_4" @click="$emit('selected', 4)">
            {{ vote.option_4 }}
        </b-btn>

        <b-btn id="option5" variant="secondary" :block="true" size="lg" v-if="vote.option_5" @click="$emit('selected', 5)">
            {{ vote.option_5 }}
        </b-btn>
    </div>
</template>

<script>
    export default {
        name: 'vote-options',

        props: {
            vote: Object
        }
    }
</script>

<style lang="scss" scoped>
    @import '~bootstrap/scss/functions';
    @import '~bootstrap/scss/variables';
    @import '~bootstrap/scss/mixins';
    
    .vote__options {
        max-width: 400px;
        margin: 0 auto;

        .btn {
            padding: 1rem 0;
            margin: 1.5rem 0;
            font-size: 1.5rem;
        }
    }
</style>