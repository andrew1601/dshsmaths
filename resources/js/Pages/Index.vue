<template>
    <layout>
        <h2>DSHS Maths Test Database</h2>
        <div class="form-group mt-3">
        <h6 class="text-muted">Choose a marksheet to view.</h6>
            <ul class="nav nav-pills">
                <li class="nav-item" v-for="a in assessmentSources" :key="a.id">
                    <inertia-link :href="`${$page.props.appUrl}/?assessment_source=${a.id}`" :class="{'nav-link': true, 'active': a.id === currentAssessmentSource}">{{ a.name }}</inertia-link>
                </li>
            </ul>

            <ul class="nav nav-pills" v-if="assessmentSource">
                <li class="nav-item" v-for="c in assessmentSource.cohorts" :key="c.id">
                    <inertia-link :href="`${$page.props.appUrl}/?assessment_source=${currentAssessmentSource}&cohort_id=${c.id}`" :class="{'nav-link': true, 'active': c.id === currentCohort}">{{ c.name }}</inertia-link>
                </li>
            </ul>

            <table class="table table-sm table-hover table-striped table-bordered mt-5" v-if="marksheet">
                <thead>
                    <tr>
                        <th rowspan="2">Student</th>
                        <th rowspan="2">IGR</th>
                        <th rowspan="2">Teaching Group</th>
                        <th class="text-center" :colspan="test.papers.length + 2" v-for="test in assessmentSource.tests" v-if="test.cohort_id === cohort.id">{{ test.name }}</th>
                    </tr>
                    <tr>
                        <template v-for="test in assessmentSource.tests" v-if="test.cohort_id===cohort.id">
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
                            <td>{{ row.student.teaching_group }}</td>
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
        'marksheet',
        'cohort'
    ],
    data() {
        return {
            currentAssessmentSource: this.assessmentSource ? this.assessmentSource.id : null,
            currentCohort: this.cohort ? this.cohort.id : null
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
