<template>
    <div>
        <div class="errors">
            <div v-for="(error, i) in errors" class="error alert alert-warning" :key="i">
                <i class="far fa-exclamation-triangle" /> {{ error.error }}
                <a href="#" @click.prevent="errors = null" class="float-right alert-link">
                    <i class="far fa-times" />
                </a>
            </div>
        </div>
        <b-card no-body>
            <h6 slot="header" class="mb-0 d-flex align-items-center">
                <strong class="mr-2">Miembros</strong> ({{ users.length }} total, {{ checkedin }} acreditados)
                <div class="ml-auto">
                    <input type="search" placeholder="Filtrar..." v-model="filter" class="form-control" />
                </div>
            </h6>

            <b-table striped hover :items="users" :fields="fields" :filter="filter">
                <template slot="table-colgroup">
                    <col width="75" />
                    <col />
                    <col width="140" />
                    <col width="240" />
                    <col width="200" />
                </template>
                <template slot="name" slot-scope="data">
                    {{ data.item.name + ' ' + data.item.last_name }}
                </template>
                <template slot="actions" slot-scope="data">
                    <div>
                        <b-btn v-if="data.item.credentials_pickedup_at === null" size="sm" variant="success" @click="checkIn(data.item)" :disabled="loadingUser === data.item.id">
                            <i :class="{ 'far': true, 'fa-check': loadingUser !== data.item.id, 'fa-spinner-third fa-spin': loadingUser === data.item.id }" />
                            Acreditar
                        </b-btn>
                        <span v-else>
                            <i class="far fa-check" /> {{ data.item.credentials_pickedup_at | dateFilter }}
                            <b-btn size="sm" variant="outline-danger" @click="undo(data.item)" class="float-right" :disabled="loadingUser === data.item.id">
                                <i :class="{ 'far': true, 'fa-times': loadingUser !== data.item.id, 'fa-spinner-third fa-spin': loadingUser === data.item.id }" />
                            </b-btn>
                        </span>
                    </div>
                </template>
            </b-table>
        </b-card>
    </div>
</template>

<script>
    const moment = require('moment');
    moment.locale('es');

    export default {
        name: 'users-list',

        data () {
            return {
                fields: [
                    {
                        key: 'group.acronym',
                        label: 'Entidad',
                        sortable: true
                    },
                    {
                        key: 'name',
                        label: 'Nombre y apellidos',
                        sortable: true
                    },
                    {
                        key: 'id_card',
                        label: 'DNI',
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
                filter: '',
                users: [],
                errors: [],
                loading: false,
                loadingUser: false
            }
        },

        mounted () {
            this.getUsers();
        },

        filters: {
            dateFilter: function (value) {
                if (!value) return '';
                return moment(value).format("ddd HH:mm");
            }
        },

        computed: {
            checkedin: function () {
                return this.users.filter(user => user.credentials_pickedup_at !== null).length;
            }
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

            checkIn (user) {
                this.loadingUser = user.id;

                API.checkIn(user.id).then(response => {
                    this.getUsers();
                }).catch((error) => {
                    this.errors.push(error);
                    this.getUsers();
                }).then(() => this.loadingUser = false);
            },

            undo (user) {
                this.loadingUser = user.id;

                const confirmed = confirm('Confirma que quieres anular la acreditación de ' + user.name + ' ' + user.last_name);

                if (confirmed) {
                    API.checkIn(user.id, true).then(response => {
                        this.getUsers();
                    })
                    .catch(errors => this.errors = errors)
                    .then(() => this.loadingUser = false);
                }
            }
        }
    }
</script>

<style lang="scss" scoped>
    .errors {
        position: fixed;
        top: 56px;
        left: 0;
        right: 0;
        z-index: 10;
    }
</style>