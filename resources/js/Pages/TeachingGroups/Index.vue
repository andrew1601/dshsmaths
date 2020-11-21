<template>
    <layout>
<!--        <select name="teachingGroupSelect" id="teachingGroupSelect" class="custom-select" v-model="selectedTeachingGroup">-->
<!--            <option :value="-1" disabled selected>Choose a teaching group...</option>-->
<!--            <option v-for="tg in teachingGroups" :key="tg.id" :value="tg.id">{{tg.name}}</option>-->
<!--        </select>-->

        <table class="table table-sm table-hover">
            <thead class="thead-dark">
                <tr>
                    <th>Teaching Group</th>
                    <th>&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="teachingGroup in teachingGroups" :key="teachingGroup.id">
                    <td><inertia-link :href="`/teaching-groups/${teachingGroup.id}`">{{teachingGroup.name}}</inertia-link></td>
                    <td><inertia-link class="text-danger">Delete</inertia-link></td>
                </tr>
            </tbody>
        </table>

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
