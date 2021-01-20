<template>
    <div>
        <div v-if="loading" class="loader">
            <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <div v-else-if="teachingGroups && teachingGroups.length > 0">
            <table class="table">
                <thead>
                <tr>
                    <th>Subject</th>
                    <th>Teaching Group</th>
                    <th>Assessment Source</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="tg in teachingGroups" :key="tg.uniqueObjectId">
                    <td>{{tg.subject.subjectName}}</td>
                    <td>{{tg.academicUnitName}}</td>
                    <td>
                        <select :id="tg.id" class="custom-select" @change="assignAssessmentToTeachingGroup">
                            <option disabled selected>Choose an assessment source...</option>
                            <option v-for="a in assessmentSources.filter(el => el.subject.id == tg.subject.id)" :key="a.uniqueObjectId" :value="a.id">{{ a.assessmentName }}</option>
                        </select>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
        <div v-else class="d-flex flex-row justify-content-center align-items-center" style="height: 100%;">
            <div class="text-muted">No teaching groups chosen :(</div>
        </div>
    </div>
</template>

<script>
import EventBus from "../../EventBus";

export default {
    name: "ArborImportAssessmentSources",

    props: [
        "subjects",
        "teachingGroups",
        "assessmentSources"
    ],

    data() {
        return {
            selectedAssessmentSources: [],
            loading: false,
        };
    },

    methods: {
        assignAssessmentToTeachingGroup(e) {
            const teachingGroupId = e.target.getAttribute('id');
            const assessmentId = e.target.value;
            const assessmentSource = this.assessmentSources.find(a => a.id == assessmentId);
            const teachingGroup = this.teachingGroups.find(tg => tg.id == teachingGroupId);

            const assignment = { teachingGroup: teachingGroup.id, assessmentSource: assessmentSource.id };
            const id = this.selectedAssessmentSources.findIndex(el => el.teachingGroup == teachingGroupId);

            if (id >= 0)
                this.selectedAssessmentSources.splice(id, 1);

            this.$set(this.selectedAssessmentSources, this.selectedAssessmentSources.length, assignment);
            EventBus.$emit('input-assessment-sources', this.selectedAssessmentSources);
        }
    }
}
</script>

<style scoped>

</style>
