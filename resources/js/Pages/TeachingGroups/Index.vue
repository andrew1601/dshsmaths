<template>
    <layout>
        <select name="teachingGroupSelect" id="teachingGroupSelect" class="custom-select" v-model="selectedTeachingGroup">
            <option :value="-1" disabled selected>Choose a teaching group...</option>
            <option v-for="tg in teachingGroups" :key="tg.id" :value="tg.id">{{tg.name}}</option>
        </select>

        <div class="card mt-3" v-if="teachingGroup">
            <div class="card-body">
                <h5 class="card-title">{{teachingGroup.name}}</h5>
                <h6 class="card-subtitle text-muted">{{teachingGroup.assessment_source.name}}</h6>

                <table class="table table-sm table-hover mt-3">
                    <thead class="thead-dark">
                    <tr>
                        <th>UPN</th>
                        <th>Last Name</th>
                        <th>First Name</th>
                        <th>Indicative</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="student in teachingGroup.students" :key="student.id">
                        <td>{{student.upn}}</td>
                        <td>{{student.last_name}}</td>
                        <td>{{student.first_name}}</td>
                        <td>{{extractIndicative(student.baselines)}}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </div>


    </layout>
</template>

<script>
export default {
    name: "Index",

    props: [
        'teachingGroups',
        'teachingGroup'
    ],

    data() {
        return {
            selectedTeachingGroup: -1,
        }
    },

    watch: {
        selectedTeachingGroup() {
            this.$inertia.get('/teaching-groups', {tg: this.selectedTeachingGroup}, {only: ['teachingGroup']});
        }
    },

    methods: {
        extractIndicative(ind) {
            if (ind[0])
                return ind[0].grade;
            else
                return '';
        }
    }

}
</script>

<style scoped>

</style>
