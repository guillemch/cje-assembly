<template>
    <div class="amendment-results row">
        <div class="col-md-6">
            <table class="table">
                <tr v-for="(group, id) in amendment.results.by_group" :key="'group' + id">
                    <td>{{ group.acronym }}</td>
                    <td>
                        <ul>
                            <li v-for="(votes, vote_for) in group.votes" :key="'group' + id + vote_for">
                                {{ votes }}
                            </li>
                        </ul>
                    </td>
                </tr>
            </table>
        </div>
        <div class="col-md-6">
            <table class="table">
                <tr v-for="option in [1, 2, 3, 4, 5]" :key="'option' + option">
                    <template v-if="amendment['option_' + option]">
                        <td>{{ amendment['option_' + option] }}</td>
                        <td>{{ amendment.results.absolute[1][option] }}</td>
                        <td>{{ amendment.results.absolute[2][option] }}</td>
                        <td>{{ amendment.results.weighted[option] }}</td>
                    </template>
                </tr>
            </table>
        </div>
    </div>
</template>

<script>
    export default {
        name: 'amendments-results',

        props: {
          amendment: Object
        }
    }
</script>

<style lang="scss" scoped>
    @import '~bootstrap/scss/functions';
    @import '~bootstrap/scss/variables';
    @import '~bootstrap/scss/mixins';

</style>