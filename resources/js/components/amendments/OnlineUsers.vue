<template>
    <div class="online-users">
        <i class="far fa-user-friends"></i>
        {{ estimate }}
        <button @click="refresh" aria-label="Refrescar" class="refresh-button">
            <i class="far fa-sync"></i>
        </button>
    </div>
</template>

<script>
    export default {
        name: 'online-users',

        data () {
            return {
                users: 0,
                interval: null
            }
        },

        computed: {
            estimate () {
                const withoutAdminAndScreen = this.users - 2;
                return withoutAdminAndScreen > 0 ? withoutAdminAndScreen : 0;
            }
        },

        mounted () {
            this.refresh();
            this.interval = setInterval(() => this.refresh(), 30000);
        },

        beforeDestroy () {
            clearInterval(this.interval);
        },

        methods: {
            refresh () {
                axios.get(process.env.MIX_SOCKETIO_SERVER + 'current')
                    .then(response => {
                        this.users = response.data.count
                    }).catch(error => {
                        console.log(error);
                    });
            }
        }
    }
</script>

<style lang="scss" scoped>
    .refresh-button {
        border: none;
        padding: 0;
        width: auto;
        overflow: visible;
        background: transparent;
        color: inherit;
        font: inherit;
        margin-left: .5rem;
        opacity: .5;

        &:hover {
            opacity: .85;
        }
    }
</style>