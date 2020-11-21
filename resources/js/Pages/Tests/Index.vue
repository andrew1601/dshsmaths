<template>
    <layout>
        <h3>Tests</h3>

       <inertia-link href="/tests/create" class="btn btn-primary">New Test</inertia-link>

        <table class="mt-3 table table-sm table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Test Name</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="test in tests">
                    <td><inertia-link :href="`/tests/${test.id}`">{{ test.name }}</inertia-link></td>
                </tr>
            </tbody>
        </table>
    </layout>
</template>

<script>
export default {
    name: "Index",
    props: [
        'tests'
    ]
}
</script>

<style scoped>

</style>
