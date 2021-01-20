<template>
    <layout>
        <h3>Tests</h3>

       <inertia-link :href="`${this.$page.props.appUrl}/tests/create`" class="btn btn-primary">New Test</inertia-link>

        <table class="mt-3 table table-sm table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Test Name</th>
                    <th>Assessment Source</th>
                    <th>Papers</th>
                    <th>Total Marks</th>
                    <th>Assigned To</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="test in tests">
                    <td><inertia-link :href="`${$page.props.appUrl}/tests/${test.id}`">{{ test.name }}</inertia-link></td>
                    <td>{{ test.assessment_source.name }}</td>
                    <td>{{ test.papers.length }}</td>
                    <td>{{ totalMarks(test) }}</td>
                    <td>
                        <div class="badge badge-secondary mr-1" v-for="teaching_group in test.teaching_groups">
                            {{ teaching_group.name }}
                        </div>
                    </td>
                    <td>
                        <inertia-link :href="`${$page.props.appUrl}/tests/${test.id}/assign`" class="btn btn-sm btn-primary">Assign</inertia-link>
                        <inertia-link :href="`${$page.props.appUrl}/tests/${test.id}/edit`" class="btn btn-sm btn-secondary">Edit</inertia-link>
                        <button class="btn btn-sm btn-danger" @click="prepForDelete(test)">Delete</button>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="modal fade" tabindex="-1" id="deleteConfirmModal" aria-hidden="true" aria-labelledby="deleteConfirmModalLabel">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="deleteConfirmModalLabel">Confirm Test Delete</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <h4>
                            <span class="badge badge-danger" v-if="deleteTarget">{{deleteTarget.name}}</span>
                            <span class="badge badge-warning" v-else>ERROR: NO DELETE TARGET SET</span>
                        </h4>
                        <p class="text-danger">Deleting this test is absolute. It cannot be undone. All student marks for this test will be lost. Please confirm deletion by typing DELETE into the textbox below.</p>
                        <div class="form-group">
                            <label for="deleteConfirmText">Confirm Delete</label>
                            <input type="text" class="form-control" id="deleteConfirmText" v-model="deleteConfirm" placeholder="Enter DELETE into this textbox">
                        </div>

                        <div class="d-flex flex-row justify-content-end">
                            <button class="btn btn-primary" @click="cancelDelete()">Cancel</button>
                            <button type="submit" class="btn btn-danger ml-2" :disabled="deleteConfirm !== 'DELETE'" @click="handleDelete()">Delete</button>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </layout>
</template>

<script>
import $ from 'jquery';

export default {
    name: "Index",
    props: [
        'tests'
    ],
    data() {
        return {
            deleteConfirm: '',
            deleteTarget: null,
        }
    },
    methods: {
        totalMarks(test) {
            let total = 0;
            test.papers.forEach(paper => {
                paper.questions.forEach(question => total += question.marks);
            });
            return total;
        },
        prepForDelete(test)
        {
            this.deleteTarget = test;
            $('#deleteConfirmModal').modal('show');
        },
        cancelDelete()
        {
            this.deleteTarget = null;
            $('#deleteConfirmModal').modal('hide');
        },
        handleDelete()
        {
            this.$inertia.delete(`${this.$page.props.appUrl}/tests/${this.deleteTarget.id}?confirmText=${this.deleteConfirm}`);
        }
    }
}
</script>

<style scoped>

</style>
