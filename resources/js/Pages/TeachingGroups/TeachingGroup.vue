<template>
    <layout>
        <div class="card">
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
    name: "TeachingGroup",

    props: [
        'teachingGroup'
    ],

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
