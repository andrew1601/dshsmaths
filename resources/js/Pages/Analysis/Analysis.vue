<template>
    <!-- not inheriting from layout -->
    <div class="mx-3">
        <div class="row">
            <div class="col-md-4">
                <img width="100%" src="/img/dshs_badge_and_text_black.png" alt="">
            </div>
            <div class="col-md-8">
                <h3 class="analysis-title">Assessment Analysis</h3>
                <h5 class="analysis-subtitle text-muted">{{ analysis.test.name }}</h5>
            </div>
        </div>

        <div class="row my-3">
            <div class="col-md-3 field-label">Name</div>
            <div class="col-md-4 field-content">{{ analysis.student.first_name }} {{ analysis.student.last_name }}</div>
            <div class="col-md-1"></div>
            <div class="col-md-2 field-label">Marks</div>
            <div class="col-md-2 field-content">{{ analysis.total_marks }}</div>
        </div>

        <div class="row">
            <div class="col-md-3 field-label">IGR</div>
            <div class="col-md-4 field-content">{{ indicativeGradeRange }}</div>
            <div class="col-md-1"></div>
            <div class="col-md-2 field-label">Grade</div>
            <div class="col-md-2 field-content">{{ analysis.grade }}</div>
        </div>

        <div class="row my-5">
            <div class="col-md-8">
                <ul class="nav nav-pills nav-fill">
                    <li class="nav-item">
                        <a href="#" @click.prevent="selectedPaperId=paper.id" v-for="paper in analysis.test.papers" :class="{'nav-link': true, 'active': selectedPaperId === paper.id}">{{ paper.name }}</a>
                    </li>
                </ul>
                <table class="table table-sm mt-3" v-for="paper in analysis.test.papers" :key="`paper-${paper.id}`" v-if="selectedPaperId === paper.id">
                    <thead class="thead-dark">
                        <tr>
                            <th>Question</th>
                            <th>Area</th>
                            <th>Topic</th>
                            <th>Max Marks</th>
                            <th>Your Marks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(question, index) in paper.questions" :key="`question-${question.id}`" :class="getRowHighlightClass(analysis.marks[paper.id][index].mark / question.marks)">
                            <td>{{ question.number }}</td>
                            <td>{{ question.area }}</td>
                            <td>{{ question.topic }}</td>
                            <td>{{ question.marks }}</td>
                            <td>{{ analysis.marks[paper.id][index].mark }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <div class="col-md-4">
                <div class="row">
                    <div class="col-md-6 field-label">Key</div>
                    <div class="col-md-6">
                        <div class="row key-label key-label-0-25">Less than 25%</div>
                        <div class="row key-label key-label-25-75">More than 25% but less than 75%</div>
                        <div class="row key-label key-label-75-100">More than 75% but less than 100%</div>
                        <div class="row key-label key-label-100">100%</div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12 mt-5">
                        <div class="row field-label">Grade Boundaries</div>
                        <div class="row">
                            <table class="table table-sm table-bordered">
                                <tr v-for="gradeBoundary in analysis.test.grade_boundaries" :key="`gb-${gradeBoundary.id}`" :class="{'active-grade-boundary': gradeBoundary.grade === analysis.grade}">
                                    <td>{{ gradeBoundary.mark }}</td>
                                    <td>{{ gradeBoundary.grade }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
</template>

<script>
export default {
    name: "Analysis",
    props: [
        'analysis',
    ],
    data() {
        return {
            selectedPaperId: 1
        }
    },
    computed: {
        indicativeGradeRange() {
            if (!this.analysis.student.baseline.grade) return 'NOIGR';
            const lower = this.analysis.student.baseline.grade;
            const higher = (lower==='B' || lower==='A') ? "A*" : String.fromCharCode(lower.charCodeAt(0)-2);

            return `${lower}-${higher}`;
        }
    },
    methods: {
        getRowHighlightClass(percentage) {
            if (percentage < 0.25)
                return 'question-highlight-0-25';
            else if (percentage >= 0.25 && percentage < 0.75)
                return 'question-highlight-25-75';
            else if (percentage >= 0.75 && percentage < 1.0)
                return 'question-highlight-75-100';
            else if (percentage === 1.0)
                return 'question-highlight-100';
            else {
                console.error(`getRowHighlightClass: ${percentage} out of bounds.`);
            }
        },
    }
}
</script>

<style scoped>

    div {
        font-family: "Neutraface Text", sans-serif;
    }

    .analysis-title {
        font-family: "Pacifico", sans-serif;
    }

    .analysis-subtitle {
        font-family: "Neutraface Text", sans-serif;
    }

    .field-label {
        background-color: rgb(200,200,200);
        font-weight: bold;
        font-size: 1.25em;
        border: 1px black solid;
        border-right: none;
        text-align: center;
        vertical-align: center;
    }

    .field-content {
        border: 1px black solid;
    }

    .key-label {
        font-size: 0.8em;
        text-align: center;
        border: 1px black solid;
        border-top: none;
        padding-left: 5px;
    }

    .key-label:first-of-type {
        border-top: 1px black solid;
    }

    .key-label-0-25, .question-highlight-0-25 {
        background: rgb(255,200,200);
    }

    .key-label-25-75 ,.question-highlight-25-75 {
        background: rgb(255,255,200);
    }

    .key-label-75-100, .question-highlight-75-100{
        background: rgb(200,255,200);
    }

    .key-label-100, .question-highlight-100 {
        background: rgb(200,200,255);
    }

    .active-grade-boundary {
        background: rgb(220, 220, 220);
    }
</style>
