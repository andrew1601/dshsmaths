<template>
    <layout>
        <h2>DSHS Maths Test Database</h2>
        <div class="form-group mt-3">
            <select name="marksheet_class_select" id="marksheetClassSelect" v-model="selectedAssessmentSource" class="custom-select">
                <option :value="null" selected disabled>Select an assessment source...</option>
                <option v-for="a in assessmentSources" :value="a.id">{{ a.name }}</option>
            </select>
            <table class="table table-sm table-hover table-striped table-bordered mt-5" v-if="assessmentSource">
                <thead>
                    <tr>
                        <th rowspan="2">Student</th>
                        <th rowspan="2">IGR</th>
                        <th rowspan="2">Teaching Group</th>
                        <th class="text-center" :colspan="test.papers.length + 2" v-for="test in assessmentSource.tests">{{ test.name }}</th>
                    </tr>
                    <tr>
                        <template v-for="test in assessmentSource.tests">
                            <th v-for="(paper, index) in test.papers">Paper {{ index + 1 }}</th>
                            <th>Total</th>
                            <th>Grade</th>
                        </template>
                    </tr>
                </thead>
                <tbody>
                        <tr v-for="row in marksheet">
                            <td>{{ row.student.last_name.toUpperCase() }} {{ row.student.first_name }}</td>
                            <td>{{ row.student.igr }}</td>
                            <td>{{ row.student.teaching_group.name }}</td>
                            <template v-for="test in row.tests">
                                <td v-for="paper in test.papers">{{ paper }}</td>
                                <td>{{ test.total_marks }}</td>
                                <td>{{ test.grade }}</td>
                            </template>
                        </tr>
                </tbody>
            </table>

<!--            <bootstrap-table :columns="columns" :data="marksheet" :options="options"></bootstrap-table>-->

        </div>
    </layout>
</template>

<script>

import BootstrapTable from 'bootstrap-table/dist/bootstrap-table-vue.esm';

export default {
    name: "Index",
    components: {BootstrapTable},
    props: [
        'assessmentSources',
        'assessmentSource',
        'marksheet'
    ],
    data() {
        return {
            selectedAssessmentSource: this.assessmentSource.id ?? null,

        }
    },
    watch: {
        selectedAssessmentSource() {
            this.$inertia.get(`${this.$page.props.appUrl}/?assessment_source=${this.selectedAssessmentSource}`);
        }
    }
}
</script>

<style scoped>

</style>
