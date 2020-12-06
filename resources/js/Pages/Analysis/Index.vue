<template>
    <layout :container="false">
        <div class="row">
            <div class="col-md-3">
                <h3>Analysis</h3>
                <div class="form-group my-3">
                    <select name="test_select" id="testSelect" v-model="selectedTestId" class="custom-select">
                        <option :value="null">Select a test...</option>
                        <option v-for="test in tests" :key="test.id" :value="test.id">{{ test.name }}</option>
                    </select>
                </div>

                <div class="form-group my-3" v-if="selectedTestId !== null">
                    <select name="teaching_group_select" id="teachingGroupSelect" v-model="selectedTeachingGroupId" class="custom-select">
                        <option :value="null">Select a teaching group...</option>
                        <option v-for="teachingGroup in selectedTest.teaching_groups" :key="teachingGroup.id" :value="teachingGroup.id">{{ teachingGroup.name }}</option>
                    </select>
                </div>

                <div class="form-group my-3" v-if="selectedTestId !== null && selectedTeachingGroupId !== null">
                    <select name="student_select" id="studentSelect" v-model="selectedStudentUpn" class="custom-select">
                        <option :value="null">Select a student...</option>
                        <option v-for="student in selectedTeachingGroup.students" :key="student.upn" :value="student.upn">{{ student.last_name.toUpperCase() }} {{ student.first_name}}</option>
                    </select>
                </div>

                <div class="form-group my-3" v-if="selectedTestId !== null && selectedTeachingGroupId !== null && selectedStudentUpn !== null">
                    <button class="btn btn-primary full-width-button" @click="loadAnalysis()">Display Analysis</button>
                </div>
            </div>

            <div class="col-md-9">
                <analysis-view v-if="analysis" :analysis="analysis"></analysis-view>
            </div>
        </div>


    </layout>
</template>

<script>
import Analysis from "./Analysis";

export default {
    name: "Index",
    components: {
        AnalysisView: Analysis
    },
    props: [
        'tests',
        'analysis',
    ],
    data() {
        return {
            selectedTestId: this.analysis ? this.analysis.test.id : null,
            selectedTeachingGroupId: this.analysis ? this.analysis.teachingGroup.id : null,
            selectedStudentUpn: this.analysis ? this.analysis.student.upn : null,
        }
    },
    computed: {
        selectedTest() {
            return this.tests.find(test => test.id === this.selectedTestId);
        },
        selectedTeachingGroup() {
            return this.selectedTest.teaching_groups.find(teachingGroup => teachingGroup.id === this.selectedTeachingGroupId);
        }
    },
    watch: {
        selectedTestId(newVal, oldVal) {
            if (!newVal) {
                this.selectedTeachingGroupId = null;
                this.selectedStudentUpn = null;
            }
        },
        selectedTeachingGroupId(newVal, oldVal) {
            if (!newVal) {
                this.selectedStudentUpn = null;
            }
        },
    },
    methods: {
        loadAnalysis() {
            this.$inertia.get(`/analysis/test/${this.selectedTestId}/teaching-group/${this.selectedTeachingGroupId}/student/${this.selectedStudentUpn}`);
        }
    }
}
</script>

<style scoped>
    .full-width-button {
        width: 100%;
    }
</style>
