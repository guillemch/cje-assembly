<template>
    <div>
        <users-table />
    </div>
</template>

<script>
    import UsersTable from './UsersTable';

    export default {
        name: 'credentials',

        components: {
            UsersTable
        }
    }
</script>