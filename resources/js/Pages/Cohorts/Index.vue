<template>
    <layout>
        <table class="table table-sm table-hover">
            <thead class="bg-dark text-white">
                <tr>
                    <th>Cohort Name</th>
                    <th></th>
                    <th>Actions</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="cohort in cohorts" :key="cohort.id">
                    <td>{{ cohort.name }}</td>
                    <td>
                        <h6 v-for="teaching_group in cohort.teaching_groups" :key="teaching_group.id"><span class="badge badge-secondary">{{ teaching_group.name }}</span></h6>
                    </td>
                    <td><button class="btn btn-sm btn-danger" @click="deleteCohort(cohort.id)">Delete</button></td>
                    <td>
                        <select class="custom-select" v-model="orphanedGroupSelect[cohort.id]" @change="addToCohort(cohort.id)">
                            <option :value="null"></option>
                            <option v-for="group in orphanedGroups" :key="group.id" :value="group.id">{{ group.name }}</option>
                        </select>
                    </td>
                </tr>
                <tr>
                    <td><input type="text" v-model="newCohortName" @keydown.enter="addCohort"></td>
                    <td><button class="btn btn-sm btn-primary" @click="addCohort">Add</button></td>
                </tr>
            </tbody>
        </table>
    </layout>
</template>

<script>
export default {
    name: "Index.vue",
    props: [
        'cohorts',
        'orphanedGroups',
    ],
    data() {
        return {
            newCohortName: null,
            orphanedGroupSelect: {},
        }
    },
    methods: {
        addCohort() {
            this.$inertia.post(`${this.$page.props.appUrl}/cohorts`, {name: this.newCohortName})
                .then(() => {
                this.newCohortName = null;
            });
        },
        deleteCohort(cohortId) {
            this.$inertia.delete(`${this.$page.props.appUrl}/cohorts/${cohortId}`);
        },
        addToCohort(cohortId) {
            this.$inertia.patch(`${this.$page.props.appUrl}/cohorts/${cohortId}`, {teaching_group: this.orphanedGroupSelect[cohortId]})
            .then(() => {
                this.orphanedGroupSelect[cohortId] = null;
            });
        }
    }
}
</script>

<style scoped>

</style>
