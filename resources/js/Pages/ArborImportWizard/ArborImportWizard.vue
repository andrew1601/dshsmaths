<template>
    <layout>
        <h2><img src="/img/arbor-icon.png" width="32" class="mr-2" alt="">Arbor Import Wizard</h2>

        <div class="alert alert-danger my-3" v-if="Object.keys(errors).length > 0">
            <h4 class="alert-heading">Oops...</h4>
            <p v-for="error in errors">{{error}}</p>
        </div>

        <div class="progress my-3" style="height: 30px;" v-if="!importing">
            <div role="progressbar"
                 :style="{'width': `${(100/steps.length)}%`}"
                 v-for="step in steps"
                 :key="step.number"
                 @click="currentStepNumber = ( (currentStepNumber > step.number) || ((currentStepNumber < step.number) && step.completed) || ((currentStepNumber === step.number - 1) && currentStep.completed ) ) ? step.number : currentStepNumber"
                 :class="{'progress-step': true, 'progress-step-active': step.number === currentStepNumber, 'progress-step-inactive': step.number !== currentStepNumber, 'progress-step-complete': step.completed}">
                <svg v-show="(step.number !== currentStepNumber && step.completed)" width="1em" height="1em" viewBox="0 0 16 16" class="mr-2 bi bi-check-circle-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                </svg>
                <span>{{ step.title }}</span>
            </div>
        </div>
        <div class="progress my-3" style="height: 30px;" v-else>
            <div role="progressbar"
                 :style="{'width': importProgress + '%'}"
                 class="progress-bar progress-bar-striped progress-bar-animated bg-primary">
                Importing...
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <h5 class="card-title">{{currentStep.title}}</h5>
                <h6 class="card-subtitle text-muted">{{currentStep.message}}</h6>
                <div class="wizard-step bg-light mt-3">
                    <keep-alive>
                        <transition
                            name="wizard-step-transition"
                            enter-active-class="animate__animated animate__zoomIn"
                            leave-active-class="animate__animated animate__zoomOut"
                            mode="out-in"
                        >
                            <component :is="currentStep.component" v-bind="stepProps" :loading="loading"></component>
                        </transition>
                    </keep-alive>
                </div>
            </div>
        </div>

        <div class="mt-2 d-flex flex-row justify-content-end align-items-center">
            <button class="btn btn-secondary mr-2" @click="currentStepNumber--" :disabled="currentStepNumber === 1">&lt; Previous</button>
            <button v-if="currentStepNumber !== 5" class="btn btn-primary" @click="currentStepNumber++" :disabled="(!currentStep.completed) && currentStep.required">Next &gt;</button>
            <button v-else class="btn btn-success" @click="doImport ">Import</button>
        </div>
    </layout>
</template>

<script>
import EventBus from "../../EventBus";

import ArborImportAcademicYear from "./ArborImportAcademicYear";
import ArborImportSubjects from "./ArborImportSubjects";
import ArborImportTeachingGroups from "./ArborImportTeachingGroups";
import ArborImportAssessmentSources from "./ArborImportAssessmentSources";
import ArborImportReview from "./ArborImportReview";

export default {
    name: "ArborImportWizard",
    mounted() {
        EventBus.$on('input-academic-year', payload => {
            this.selectedAcademicYear = payload;
            this.currentStep.completed = payload !== null;
            this.$inertia.patch('/arbor-import', {academic_year: this.selectedAcademicYear}, {preserveState: true})
        });

        EventBus.$on('input-subjects', payload => {
            this.selectedSubjects = payload;
            this.selectedTeachingGroups = this.selectedTeachingGroups.filter(tg => payload.findIndex(s => s.id === tg.subject.id) >= 0);
            this.currentStep.completed = payload.length > 0;
            this.$inertia.patch('/arbor-import', {academic_year: this.selectedAcademicYear, subjects: payload.map(s => s.id)}, {preserveState: true, only: ['teachingGroups', 'assessmentSources'] });
        });

        EventBus.$on('input-teaching-groups', payload => {
            this.selectedTeachingGroups = payload;
            this.currentStep.completed = payload.length > 0;
        });

        EventBus.$on('input-assessment-sources', payload => {
            this.selectedAssessmentSources = payload;
            this.currentStep.completed = payload.length > 0;
        })
    },

    props: [
        'academicYears',
        'subjects',
        'teachingGroups',
        'assessmentSources',
        'errors'
    ],
    data() {
        return {
            loading: false,
            importing: false,
            importProgress: 0,
            currentStepNumber: 1,
            steps: [
                {
                    number: 1,
                    required: true,
                    completed: false,
                    title: 'Academic Year',
                    message: 'Select the academic year that you want to import from.',
                    component: ArborImportAcademicYear
                },
                {
                    number: 2,
                    required: true,
                    completed: false,
                    title: 'Subjects',
                    message: 'Select the subjects that you want to import teaching groups from.',
                    component: ArborImportSubjects
                },
                {
                    number: 3,
                    required: true,
                    completed: false,
                    title: 'Teaching Groups',
                    message: 'Select all the teaching groups that you want to import.',
                    component: ArborImportTeachingGroups
                },
                {
                    number: 4,
                    required: false,
                    completed: false,
                    title: 'Assessment Sources',
                    message: 'Please select an assessment source for each selected teaching group.',
                    component: ArborImportAssessmentSources
                },
                {
                    number: 5,
                    required: true,
                    completed: false,
                    title: 'Review',
                    message: 'You have selected the following teaching groups to import.',
                    component: ArborImportReview
                }
            ],
            selectedAcademicYear: null,
            selectedSubjects: [],
            selectedTeachingGroups: [],
            selectedAssessmentSources: [],
        }
    },
    computed: {
        stepProps() {
            switch(this.currentStepNumber) {
                case 1:
                    return { academicYears: this.academicYears }
                case 2:
                    return { subjects: this.subjects };
                case 3:
                    return { selectedSubjects: this.selectedSubjects, teachingGroups: this.teachingGroups };
                case 4:
                    return { subjects: this.subjects, teachingGroups: this.selectedTeachingGroups, assessmentSources: this.assessmentSources };
                case 5:
                    return { subjects: this.selectedSubjects, teachingGroups: this.selectedTeachingGroups, assessmentSources: this.assessmentSources, assignments: this.selectedAssessmentSources };
                default:
                    return {};
            }
        },
        currentStep() {
            return this.steps.find(step => step.number === this.currentStepNumber);
        }
    },
    methods: {
        doImport() {
            let intervalId;
            let pairingsArray = [];

            this.selectedAssessmentSources.forEach(pairing => {
                pairingsArray.push([pairing.teachingGroup, pairing.assessmentSource]);
            });

            const assessmentSources = Object.fromEntries(pairingsArray);
            const teachingGroups = this.selectedTeachingGroups.map(tg => tg.id);

            this.$inertia.post('/arbor-import', { academic_year: this.selectedAcademicYear, teaching_groups: teachingGroups, assessment_sources: assessmentSources }, {
                onStart: () => {
                    this.importing = true;
                    this.importProgress = 0;
                    intervalId = setInterval(() => { if (this.importProgress <= 95) this.importProgress += 5}, 800);
                },
                onFinish: () => {
                    clearInterval(intervalId);
                    this.importProgress = 100;
                },
                preserveState: true,
            });
        }
    },
}
</script>

<style lang="scss" scoped>

    @import '../../../scss/app';

    .progress-step {
        @extend .progress-bar;
        display: flex;
        flex-direction: row;
        justify-content: center;
        align-items: center;
    }

    .progress-step-active
    {
        @extend .bg-primary;
        @extend .text-white;
    }

    .progress-step-inactive:hover
    {
        background-color: #eaeaea !important;
        cursor: pointer;
    }

    .progress-step-complete.progress-step-inactive
    {
        color: theme-color("success") !important;
    }

    .progress-step-inactive
    {
        @extend .bg-light;
        @extend .text-dark;
    }

    .wizard-step {
        height: 50vh;
        max-height: 50vh;
        overflow-y: scroll;
    }

</style>
