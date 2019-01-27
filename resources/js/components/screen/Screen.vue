<template>
    <div class="screen">
        <div v-if="screen.vote">
            <div class="vote-name">
                <h1>{{ screen.vote.name }}</h1>
            </div>
            <div class="vote-results">
                {{ screen.vote.results }}
            </div>
            <div class="time-password">
                {{ screen.code }}
            </div>
        </div>
        <div v-else>
            Logo
        </div>
    </div>
</template>

<script>
    export default {
        name: 'screen',

        data () {
            return {
                screen: { code: null, vote: null },
                loading: false,
                connected: false,
                interval: null
            };
        },

        mounted () {
            this.getScreen(true);
        },

        sockets: {
            connect: function () {
                this.connected = true;
            },
            refresh_vote: function (data) {
                this.getScreen(true);
            },
            disconnect: function () {
                this.connected = false;
            }
        },

        methods: {
            getScreen (allowRefresh) {
                this.loading = true;
                console.log('Getting...');
                API.getScreen().then(screen => {
                    this.screen = screen;
                    this.loading = false;

                    if (this.screen.vote) {
                      if (allowRefresh) this.interval = setInterval(() => { this.getScreen(false); }, 1000);
                    } else {
                      clearInterval(this.interval);
                      this.interval = null;
                    }
                });
            }
        }
    }
</script>

<style lang="scss" scoped>
    @import '~bootstrap/scss/functions';
    @import '~bootstrap/scss/variables';
    @import '~bootstrap/scss/mixins';
</style>