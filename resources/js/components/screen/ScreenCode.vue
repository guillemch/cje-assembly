<template>
    <div class="screen-code">
        <i class="far fa-key" />
        {{ code }}
    </div>
</template>

<script>
    export default {
        name: 'screen-code',

        props: {
            code: String
        }
    }
</script>

<style lang="scss" scoped>
    .screen-code {
        letter-spacing: 1.5vw;
    }
</style>