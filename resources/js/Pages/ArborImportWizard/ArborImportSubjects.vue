<template>
    <div class="row m-2">
        <div class="col-5 subject-select-box">
            <h5>Available Subjects</h5>
            <input type="text" v-model="subjectFilterText">
            <div @click="toggleSubject(subject.id)" v-for="subject in filteredSubjectList" :key="subject.id" class="subject-list-item" v-if="selectedSubjects.findIndex(s => s.id === subject.id) === -1">
                {{subject.displayName}}
            </div>
        </div>
        <div class="col-2">

        </div>
        <div class="col-5 subject-select-box">
            <h5>Selected Subjects</h5>
            <div @click="toggleSubject(subject.id)" v-for="subject in selectedSubjects" :key="subject.id" class="subject-list-item">
                {{subject.displayName}}
            </div>
        </div>
    </div>
</template>

<script>
import EventBus from "../../EventBus";

export default {
    name: "ArborImportSubjects",
    props: [
        'subjects'
    ],
    data() {
        return {
            selectedSubjects: [],
            subjectFilterText: ""
        }
    },
    computed: {
        filteredSubjectList() {
            return this.subjects.filter(s => s.displayName.toLowerCase().indexOf(this.subjectFilterText.toLowerCase()) !== -1);
        }
    },
    methods: {
        toggleSubject(subjectId){
            const subjectIndex = this.selectedSubjects.findIndex(s => s.id === subjectId);
            const subject = this.subjects.find(s => s.id === subjectId);
            if (subjectIndex !== -1)
            {
                this.selectedSubjects.splice(subjectIndex, 1);
            } else {
                this.$set(this.selectedSubjects,this.selectedSubjects.length,subject);
            }

            EventBus.$emit('input-subjects', this.selectedSubjects);
        }
    }
}
</script>

<style scoped>

    .row {
        height: 100%;
    }

    .subject-select-box {
        background: #bbb;
        height: 95%;
        max-height: 95%;
        overflow-y: scroll;
    }

    .subject-list-item {
        background: #ddd;
        margin: 2px 0px;
        padding: 2px 2px;
        text-wrap: none;
    }

    .subject-list-item:hover {
        background: lightblue;
        cursor: pointer;
    }


</style>
