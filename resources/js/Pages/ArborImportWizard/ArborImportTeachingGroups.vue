<template>
    <div class="row m-2">
        <div class="col-5 teaching-group-list">
            <h5>Available Teaching Groups</h5>
            <div @click="toggleTeachingGroup(teachingGroup.id)" v-for="teachingGroup in teachingGroups" :key="teachingGroup.id" class="teaching-group-list-item" v-if="selectedTeachingGroups.findIndex(tg => tg.id === teachingGroup.id) === -1">
                {{teachingGroup.academicUnitName}}
            </div>
        </div>
        <div class="col-2">

        </div>
        <div class="col-5 teaching-group-list">
            <h5>Selected Teaching Groups</h5>
            <div @click="toggleTeachingGroup(teachingGroup.id)" v-for="teachingGroup in selectedTeachingGroups" :key="teachingGroup.id" class="teaching-group-list-item">
                {{teachingGroup.academicUnitName}}
            </div>
        </div>
    </div>
</template>

<script>
import EventBus from "../../EventBus";

export default {
    name: "ArborImportTeachingGroups",
    props: [
        'teachingGroups'
    ],
    data() {
        return {
            selectedTeachingGroups: []
        }
    },
    methods: {
        toggleTeachingGroup(teachingGroupId) {
            const teachingGroupIndex = this.selectedTeachingGroups.findIndex(tg => tg.id === teachingGroupId);
            const teachingGroup = this.teachingGroups.find(tg => tg.id === teachingGroupId);

            if (teachingGroupIndex > -1)
                this.selectedTeachingGroups.splice(teachingGroupIndex, 1);
            else
                this.$set(this.selectedTeachingGroups, this.selectedTeachingGroups.length, teachingGroup);

            EventBus.$emit('input-teaching-groups', this.selectedTeachingGroups);
        }
    },
}
</script>

<style scoped>

.row {
    height: 100%;
}

.teaching-group-list {
    background: #bbb;
    height: 95%;
    max-height: 95%;
    overflow-y: scroll;
}

.teaching-group-list-item {
    background: #ddd;
    margin: 2px 0px;
    padding: 2px 2px;
    text-wrap: none;
}

.teaching-group-list-item:hover {
    background: lightblue;
    cursor: pointer;
}

</style>
