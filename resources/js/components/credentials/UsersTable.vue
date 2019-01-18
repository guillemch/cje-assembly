<template>
    <b-card>
        <h6 slot="header" class="mb-0">Miembros</h6>
        
        <b-table striped hover :items="users" :fields="fields">
            <template slot="actions" slot-scope="data">
                <div>
                    <b-btn v-if="data.item.credentials_pickedup_at === null" size="sm" variant="success" @click="checkIn(data.item.id)">Acreditar</b-btn>
                    <span v-else>
                        <i class="far fa-check" /> {{ data.item.credentials_pickedup_at }}
                    </span>
                </div>
            </template>
        </b-table>
    </b-card>
</template>

<script>
    export default {
        name: 'users-list',

        data () {
            return {
                fields: [
                    {
                        key: 'id',
                        label: '#',
                        sortable: true
                    },
                    {
                        key: 'name',
                        label: 'Nombre y apellidos',
                        sortable: true
                    },
                    {
                        key: 'email',
                        label: 'E-mail',
                        sortable: true
                    },
                    {
                        key: 'actions',
                        label: 'Acciones'
                    }
                ],
                users: [],
                errors: [],
                loading: false
            }
        },

        mounted () {
            this.getUsers();
        },

        methods: {
            getUsers () {
                this.loading = true;

                API.getUsers().then(response => {
                    this.users = response;
                }).catch(error => {
                    alert('Error al cargar los usuarios. Refresca el navegador');
                }).then(() => this.loading = false);
            },

            checkIn (userId) {
                API.checkIn(userId).then(response => {
                    this.getUsers();
                })
                .catch(errors => this.errors = errors)
                .then(() => this.loading = false);
            }
        }
    }
</script>