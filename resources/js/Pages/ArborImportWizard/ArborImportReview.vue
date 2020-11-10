<template>
    <div>
        <table class="table">
            <thead>
            <tr>
                <th>Subject</th>
                <th>Teaching Group</th>
                <th>Assessment Source</th>
            </tr>
            </thead>
            <tbody>
            <tr v-for="teachingGroup in teachingGroups" :key="teachingGroup.id">
                <td>{{ teachingGroup.subject.displayName }}</td>
                <td>{{ teachingGroup.academicUnitName }}</td>
                <td>{{ getAssessmentSource(teachingGroup.id) }}</td>
            </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
export default {
    name: "ArborImportReview",

    props: [
        'teachingGroups',
        'subjects',
        'assessmentSources',
        'assignments'
    ],
    methods: {
        getAssessmentSource(teachingGroupId) {
            const assessmentSourceId = this.assignments.find(el => el.teachingGroup === teachingGroupId);
            return assessmentSourceId ? this.assessmentSources.find(el => el.id === assessmentSourceId.assessmentSource).assessmentName : "No assessment source selected.";
        }
    }
}
</script>

<style scoped>

</style>
