<template>
    <layout>
        <h3>{{ editing ? 'Edit' : 'Create' }} a Test</h3>

        <div class="alert alert-danger my-3" v-if="Object.keys(errors).length > 0">
            <h4 class="alert-heading">Oops...</h4>
            <p v-for="error in errors">{{error}}</p>
        </div>

        <div class="form-group mt-3">
            <input class="form-control" type="text" id="testName" placeholder="Test name" v-model="testName">
        </div>

        <div class="form-group mt-3">
            <select name="test_assessment_source" id="testAssessmentSource" class="custom-select" v-model="selectedAssessmentSource">
                <option :value="null" selected disabled>Select an assessment source to associate this test with...</option>
                <option v-for="assessmentSource in assessmentSources" :key="assessmentSource.id" :value="assessmentSource.id">{{ assessmentSource.name }}</option>
            </select>
        </div>

        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header d-flex flex-row justify-content-between">
                        <ul class="nav nav-pills card-header-pills">
                            <li class="nav-item d-flex flex-row justify-content-between" v-for="(paper, index) in papers" :key="`paper-${index+1}`">
                                <a href="#" :class="{'nav-link': true, 'active': index === currentPaperIndex}" @click="currentPaperIndex=index">
                                    Paper {{ index + 1 }}
                                    <span class="pl-3" v-show="(papers.length > 1) && (index === currentPaperIndex)">
                                        <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-x-circle" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                        <path fill-rule="evenodd" d="M4.646 4.646a.5.5 0 0 1 .708 0L8 7.293l2.646-2.647a.5.5 0 0 1 .708.708L8.707 8l2.647 2.646a.5.5 0 0 1-.708.708L8 8.707l-2.646 2.647a.5.5 0 0 1-.708-.708L7.293 8 4.646 5.354a.5.5 0 0 1 0-.708z"/>
                                    </svg>
                                    </span>
                                </a>
                            </li>
                        </ul>

                        <button class="btn btn-outline-secondary" @click="addPaper">+ Add</button>
                    </div>
                    <div class="card-body">
                        <h6 class="text-muted" v-if="papers.length === 0">No papers.</h6>
                        <h6 class="text-muted" v-else-if="currentPaperIndex === -1">No paper selected.</h6>
                        <create-paper v-model="papers[currentPaperIndex]" v-else></create-paper>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 mt-3 mt-lg-0">
                <div class="card">
                    <h5 class="card-header">
                        Grade Boundaries
                    </h5>
                    <div class="card-body">
                        <hot-table ref="gradeBoundariesHot" :settings="gradeBoundariesHotSettings"></hot-table>
                    </div>
                </div>
            </div>

        </div>

        <div class="row mt-3">
            <div class="col d-flex flex-row-reverse">
                <button class="btn btn-primary" @click="submit">Submit</button>
            </div>
        </div>

    </layout>
</template>

<script>
import CreatePaper from "./CreatePaper";

import { HotTable } from '@handsontable/vue';

export default {
    name: "Create",

    components: {
        CreatePaper,
        HotTable
    },

    props: [
        'editing',
        'test',
        'errors',
        'assessmentSources'
    ],
    created() {
        if (this.editing){
            console.dir(this.test) // need to see this data, but bug with Vue devtools and refs on HOT instances.
            // load in the data
            this.testName = this.test.name;
            this.papers = this.test.papers.map(paper => ({
                name: paper.name,
                questions: paper.questions.map(question => ({
                    number: question.number,
                    area: question.area,
                    topic: question.topic,
                    marks: question.marks
                }))
            }));
            this.gradeBoundaries = this.test.grade_boundaries.map(grade_boundary => { return {grade: grade_boundary.grade, marks: grade_boundary.mark} });
            this.selectedAssessmentSource = this.test.assessment_source_id;
        }
    },
    mounted(){
        this.$refs.gradeBoundariesHot.hotInstance.loadData(this.gradeBoundaries);
    },

    data() {
        return {
            testName: '',
            selectedAssessmentSource: null,
            maxPapers: 4,
            currentPaperIndex: -1,
            papers: [],
            gradeBoundaries: [],
            gradeBoundariesHotSettings: {
                dataSchema: {grade: null, marks: null},
                data: this.gradeBoundaries,
                colHeaders: ['Grade', 'Min. Mark'],
                stretchH: 'all',
                width: '100%',
                minSpareRows: 1,
                colWidths: [1,1],
                startCols: 2,
                licenseKey: 'non-commercial-and-evaluation'

            }
        }
    },
    methods: {
        addPaper() {
            if (this.papers.length >= this.maxPapers) return;

            const newPaper = {
                name: '',
                questions: [],
            };

            this.$set(this.papers, this.papers.length, newPaper);
        },
        submit() {
            const name = this.testName;

            // remove empty last row from HOT data sources
            // first the grade boundaries
            const gradeBoundaries = this.gradeBoundaries.filter(row => (row.grade !== null) && (row.marks !== null));
            // finally, the papers
            // const papers = this.papers.forEach(paper => {
            //     paper.questions = paper.questions.filter(row => (row.number !== null) && (row.area !== null) && (row.topic !== null) && (row.marks !== null));
            // });

            const papers = this.papers.map(paper => ({
                ...paper,
                questions: paper.questions.filter(row => !((row.number === null) && (row.area === null) && (row.topic === null) && (row.marks === null)))
            }));

            this.$inertia.post(`${this.$page.props.appUrl}/tests`, {name, assessmentSource: this.selectedAssessmentSource, papers, gradeBoundaries});
        }
    },
    computed: {
    }
}
</script>

<style scoped>

.paper-button {
    cursor: pointer;
}
</style>
