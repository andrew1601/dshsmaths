<template>
    <layout>
        <h3>Data Entry Component</h3>

        <div class="form-group">
            <select name="test_select" id="testSelect" class="custom-select" v-model="selectedTest">
                <option :value="-1" selected disabled>Select a test...</option>
                <option v-for="(test, index) in tests" :key="test.id" :value="test.id">{{ test.name }}</option>
            </select>
        </div>

        <div class="form-group" v-if="selectedTest >= 0">
            <select name="paper_select" id="paperSelect" class="custom-select" v-model="selectedPaper">
                <option :value="-1" selected disabled>Select a paper...</option>
                <option v-for="(paper, index) in test.papers" :key="paper.id" :value="paper.id">Paper {{index + 1}}: {{ paper.name }}</option>
            </select>
        </div>

        <div class="form-group" v-if="(selectedTest >= 0) && (selectedPaper >= 0)">
            <select name="teaching_group_select" id="teachingGroupSelect" class="custom-select" v-model="selectedTeachingGroup">
                <option :value="-1" selected disabled>Select a teaching group...</option>
                <option v-for="teachingGroup in teachingGroups" :key="teachingGroup.id" :value="teachingGroup.id">{{ teachingGroup.name }}</option>
            </select>
        </div>

        <div class="form-group" v-if="(selectedTest >= 0) && (selectedPaper >= 0) && (selectedTeachingGroup >= 0)">
            <inertia-link :href="`${this.$page.props.appUrl}/data-entry/paper/${selectedPaper}/teaching-group/${selectedTeachingGroup}`" class="btn btn-primary full-width-button">Open Spreadsheet</inertia-link>
        </div>
    </layout>
</template>

<script>
export default {
    name: "Index",

    props: [
        'tests'
    ],

    data() {
        return {
            selectedTest: -1,
            selectedPaper: -1,
            selectedTeachingGroup: -1,
        }
    },

    computed: {
        teachingGroups() {
            const test = this.tests.find(t => t.id === this.selectedTest);
            return test.teaching_groups;
        },
        test() {
            return this.tests.find(t => t.id === this.selectedTest);
        }
    }
}
</script>

<style scoped>
    .full-width-button {
        width: 100%;
    }
</style>
