<template>
    <b-form @submit.prevent="requestFloor">
        <b-form-group
            id="floorTimeGroup"
            label="Tiempo"
            label-for="time">
            <input type="time" ref="time" v-model="time" class="form-control input-lg input-block text-center" />
        </b-form-group>
        <b-form-group
            id="floorSpeakerGroup"
            label="Ponente"
            label-for="speaker"
            v-if="speakers">
            <b-form-select id="speaker" v-model="speaker" :options="speakers" class="form-control input-lg input-block" />
            <input v-model="customSpeaker" class="form-control input-lg input-block mt-2" :disabled="speaker" />
        </b-form-group>
        <div class="text-right">
          <hr />
          <b-btn type="submit" variant="primary">Iniciar turno</b-btn>
        </div>
    </b-form>
</template>

<script>
    export default {
        name: 'floor-new',

        data () {
            return {
                time: '02:00',
                speaker: null,
                customSpeaker: '',
                speakers: null,
                loading: false,
                errors: []
            }
        },

        mounted () {
            this.speakers = [
                {
                    text: '',
                    value: null
                }
            ];
            config.users.forEach(user => {
                this.speakers.push({
                    text: user.last_name + ', ' + user.name + ' (' + user.group.acronym + ')',
                    value: {
                      name: user.name + ' ' + user.last_name,
                      group: user.group
                    }
                });
            });
        },

        methods: {
            requestFloor () {
                const time = this.time.split(":");
                const milliseconds = ((parseInt(time[0]) * 60) + (parseInt(time[1]))) * 1000;
                this.$socket.emit('new_speaker', {
                    speaker: (this.customSpeaker) ? { name: this.customSpeaker, group: { name: '', acronym: '--' } } : this.speaker,
                    time: milliseconds
                });
                this.resetFloor();
                this.$root.$emit('bv::hide::modal', 'FloorNew', '#FloorNewButton');
            },

            autofocus () {
                this.$refs.time.focus();
            },

            resetFloor() {
                this.speaker = null;
                this.customSpeaker = '';
                this.loading = false;
                this.errors = [];
            }
        }
    }
</script>

<style lang="scss" scoped>
  input[type=time] {
    font-size: 1.4rem;
  }
</style>