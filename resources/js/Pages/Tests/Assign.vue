<template>
    <layout>
        <h3>Test Assignment</h3>
        <h4 class="text-muted">{{test.name}}</h4>
        <h5 class="text-muted">{{test.assessment_source.name}}</h5>

        <div class="alert alert-danger my-3" v-if="Object.keys(errors).length > 0">
            <h4 class="alert-heading">Oops...</h4>
            <p v-for="error in errors">{{error}}</p>
        </div>

        <div class="row my-5">
            <div class="col-6">
                <div class="card">
                    <h5 class="card-header">Available Assignments</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item" v-for="teachingGroup in teachingGroups" :key="teachingGroup.id" @click="toggleAssign(teachingGroup.id)">
                            {{ teachingGroup.name }}
                        </li>
                    </ul>
                </div>
            </div>

            <div class="col-6">
                <div class="card">
                    <h5 class="card-header">Current Assignments</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item" v-for="teachingGroup in test.teaching_groups" :key="teachingGroup.id" @click="toggleAssign(teachingGroup.id)">
                            {{ teachingGroup.name }}
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </layout>
</template>

<script>
export default {
    name: "Assign",
    props: [
        'errors',
        'test',
        'teachingGroups',
        'assignments',
    ],
    methods: {
        toggleAssign(teachingGroupId) {
            this.$inertia.patch(`/tests/${this.test.id}/assign`, {teachingGroupId});
        },
    }
}
</script>

<style scoped>

    .list-group-item:hover {
        background-color: rgba(0, 0, 0, 0.075);
        cursor: pointer;
    }

</style>
