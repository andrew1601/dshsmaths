<template>
    <div class="container pt-3">
        <select name="academic_year_select" id="academicYearSelect" class="custom-select" v-model="selectedAcademicYear">
            <option :value="null" selected disabled>Select an academic year...</option>
            <option v-for="academicYear in academicYears" :key="academicYear.id" :value="academicYear.id">
                {{ academicYear.displayName }}
            </option>
        </select>
    </div>
</template>

<script>
import EventBus from "../../EventBus";

export default {
    name: "ArborImportAcademicYear",
    props: [
        'academicYears'
    ],
    data() {
        return {
            selectedAcademicYear: null,
        }
    },
    watch: {
        selectedAcademicYear() {
            EventBus.$emit('input-academic-year', this.selectedAcademicYear);
        }
    },
}
</script>

<style scoped>

</style>
