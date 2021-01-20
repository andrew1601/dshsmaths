<template>
    <layout>
        <h3>{{test.name}}</h3>

        <div class="btn-group mt-3">
            <button type="button" :class="{'paper-select-btn': true,  'btn-outline-primary': index !== selectedPaper, 'btn-primary': index===selectedPaper}" v-for="(paper, index) in test.papers" @click="selectedPaper = index">Paper {{index + 1}}</button>
        </div>

        <div class="row mt-5">
            <div class="col-lg-8">
                <table v-for="(paper, i) in test.papers" :key="i" class="table table-sm table-hover" v-if="i === selectedPaper">
                    <thead class="thead-dark">
                    <tr>
                        <th colspan="4">Paper {{i+1}} (<i>{{paper.name || 'Untitled'}}</i>)</th>
                    </tr>
                    <tr>
                        <th>Number</th>
                        <th>Area</th>
                        <th>Topic</th>
                        <th>Marks</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(question, index) in paper.questions" :key="index">
                        <td>{{question.number}}</td>
                        <td>{{question.area}}</td>
                        <td>{{question.topic}}</td>
                        <td>{{question.marks}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <div class="col-lg-4">
                <table class="table table-sm table-hover">
                    <thead class="thead-dark">
                    <tr>
                        <th colspan="2">Grade Boundaries</th>
                    </tr>
                    <tr>
                        <th>Grade</th>
                        <th>Mark</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(gradeBoundary, index) in test.grade_boundaries" :key="`gb${index}`">
                        <td>{{gradeBoundary.grade}}</td>
                        <td>{{gradeBoundary.mark}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </layout>
</template>

<script>
export default {
    name: "Show",
    props: [
        'test'
    ],
    data() {
        return {
            selectedPaper: 0,
        }
    }
}
</script>

<style lang="scss" scoped>
    @import '../../../scss/app';

    .paper-select-btn {
        @extend .btn;
    }

    .paper-select-btn:focus {
        outline: none;
        box-shadow: none;
    }
</style>
