<template>
    <layout>
        <h3>Test Assignment</h3>
        <h4 class="text-muted">{{test.name}}</h4>
        <h5 class="text-muted">{{test.assessment_source.name}}</h5>
        <p class="text-muted">Click on a teaching group to assign this test to them. You can only assign to a teaching group that is associated with the same assessment source as this test. Click on an assigned teaching group to unassign this test. Student marks will not be lost when you unassign a test.</p>

        <div class="alert alert-danger my-3" v-if="Object.keys(errors).length > 0">
            <h4 class="alert-heading">Oops...</h4>
            <p v-for="error in errors">{{error}}</p>
        </div>

        <div class="row my-5">
            <div class="col-6">
                <div class="card">
                    <h5 class="card-header">Available Teaching Groups</h5>
                    <ul class="list-group list-group-flush" v-if="teachingGroups.length > 0">
                        <li class="list-group-item available-teaching-group" v-for="teachingGroup in teachingGroups" :key="teachingGroup.id" @click="toggleAssign(teachingGroup.id)">
                            {{ teachingGroup.name }}
                        </li>
                    </ul>
                    <div class="card-body" v-else>
                        <p class="text-muted text-center">No teaching groups available :(</p>
                    </div>
                </div>
            </div>

            <div class="col-6">
                <div class="card">
                    <h5 class="card-header">Assigned Teaching Groups</h5>
                    <ul class="list-group list-group-flush" v-if="test.teaching_groups.length > 0">
                        <li class="list-group-item assigned-teaching-group" v-for="teachingGroup in test.teaching_groups" :key="teachingGroup.id" @click="toggleAssign(teachingGroup.id)">
                            {{ teachingGroup.name }}
                        </li>
                    </ul>
                    <div class="card-body" v-else>
                        <p class="text-muted text-center">No teaching groups assigned :(</p>
                    </div>
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

    .assigned-teaching-group:hover {
        background-color: rgba(255, 0, 0, 0.075);
        cursor: pointer;
    }

    .available-teaching-group:hover {
        background-color: rgba(0, 0, 0, 0.075);
        cursor: pointer;
    }

</style>
