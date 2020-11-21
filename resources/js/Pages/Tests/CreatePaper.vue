<template>
    <div>
        <div class="row mt-3">
            <div class="col">
                <div class="form-group">
                    <input class="form-control" type="text" id="paperName" placeholder="Paper name" v-model="paper.name">
                </div>
            </div>
<!--   Was going to add a raw paper type as well as qla, but ditched it for now because I couldn't be bothered to think about how to store the data efficiently  -->
            <!-- I'll leave it commented in in case I decide to come back to it later... -->
<!--            <div class="col-3">-->
<!--                <div class="btn-group">-->
<!--                    <button :class="{'paper-type-btn': true, 'btn': true, 'btn-primary': paperType === 'qla', 'btn-outline-primary': paperType !== 'qla'}" @click="paperType='qla'">QLA</button>-->
<!--                    <button :class="{'paper-type-btn': true, 'btn': true, 'btn-primary': paperType === 'raw', 'btn-outline-primary': paperType !== 'raw'}" @click="paperType='raw'">Raw</button>-->
<!--                </div>-->
<!--            </div>-->
        </div>

        <div class="row mt-3 d-flex flex-row justify-content-center align-items-center" v-if="paperType === 'qla'">
            <hot-table ref="paperHot" :settings="hotSettings"></hot-table>
        </div>

        <div v-else>
            <div class="form-group">
                <input type="number" class="form-control" id="paperMarks" placeholder="Total marks">
            </div>
        </div>



    </div>
</template>

<script>

import { HotTable } from '@handsontable/vue';

export default {
    name: "CreatePaper",
    components: {
        HotTable
    },
    props: [
        'value'
    ],
    mounted() {
        this.$refs.paperHot.hotInstance.loadData(this.value.questions);
    },

    data() {
        return {
            paperType: 'qla',
            paper: this.value,
            hotSettings: {
                dataSchema: {number: null, area: null, topic: null, marks: null },
                data: [],
                colWidths: [2,3,3,1],
                colHeaders: ['Question', 'Area', 'Topic', 'Marks'],
                minSpareRows: 1,
                startCols: 4,
                stretchH: 'all',
                width: '100%',
                licenseKey: 'non-commercial-and-evaluation'
            }
        }
    },
    watch: {
        paper() {
            this.$emit('input', this.paper);
        },
        value() {
            this.paper = this.value;
            this.$refs.paperHot.hotInstance.loadData(this.value.questions);
        }

    }
}
</script>

<style scoped>
    .paper-type-btn:focus {
        outline: none;
        box-shadow: none;
    }
</style>
