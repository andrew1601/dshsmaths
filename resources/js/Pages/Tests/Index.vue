<template>
    <layout>
        <h3>Tests</h3>

       <inertia-link href="/tests/create" class="btn btn-primary">New Test</inertia-link>

        <table class="mt-3 table table-sm table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Test Name</th>
                    <th>Papers</th>
                    <th>Total Marks</th>
                    <th>Assigned To</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="test in tests">
                    <td><inertia-link :href="`/tests/${test.id}`">{{ test.name }}</inertia-link></td>
                    <td>{{ test.papers.length }}</td>
                    <td>{{ totalMarks(test) }}</td>
                    <td></td>
                    <td>
                        <inertia-link :href="`/tests/${test.id}/assign`" class="btn btn-sm btn-primary">Assign</inertia-link>
                        <inertia-link as="button" method="delete" :href="`/tests/${test.id}`" class="btn btn-sm btn-danger">Delete</inertia-link>
                    </td>
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
    ],
    methods: {
        totalMarks(test) {
            let total = 0;
            test.papers.forEach(paper => {
                paper.questions.forEach(question => total += question.marks);
            });
            return total;
        }
    }
}
</script>

<style scoped>

</style>
