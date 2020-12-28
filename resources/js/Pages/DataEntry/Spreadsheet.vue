<template>
    <layout :container="false">
        <div class="px-3">
            <h4>Spreadsheet View</h4>
            <h5><span class="badge badge-secondary">{{paper.test.name}}</span> / <span class="badge badge-secondary lighten">{{paper.name}}</span> / <span class="badge badge-primary">{{teachingGroup.name}}</span></h5>
            <hot-table class="spreadsheet" ref="spreadsheet" :settings="hotSettings">
                <hot-column data="upn"></hot-column> <!-- hidden column -->
                <hot-column v-for="question in paper.questions" :key="question.id" :data="question.id" :settings="{ validator: getMaxMarkValidator(question.marks) }"></hot-column>
            </hot-table>
        </div>

        <div class="console px-3 my-3" v-if="showConsole">
            <ul>
                <li v-for="change in changes">{{change}}</li>
            </ul>
        </div>

    </layout>
</template>

<script>
import axios from 'axios';
import { HotTable, HotColumn } from '@handsontable/vue';

export default {
    name: "Spreadsheet",
    components: {
        HotTable,
        HotColumn
    },
    props: [
        'paper',
        'teachingGroup',
        'marks',
        'dataSchema'
    ],
    data() {
        return {
            showConsole: true,
            changes: [],
            hotSettings: {
                startRows: this.teachingGroup.students.length,
                startCols: this.paper.questions.length,
                preventOverflow: 'horizontal',
                enterMoves: {row: 0, col: 1},
                rowHeaderWidth: 300,
                invalidCellClassName: 'invalid-mark',
                stretchH: 'all',
                dataSchema: this.dataSchema,
                afterChange: this.saveMark,
                data: this.marks,
                rowHeaders: this.teachingGroup.students.map(student => `${student.last_name.toUpperCase()} ${student.first_name}`),
                nestedHeaders: [
                    ['upn', ...this.paper.questions.map(question => question.number)],
                    ['', ...this.paper.questions.map(question => question.marks)]
                ],
                // colHeaders: true,
                hiddenColumns: {
                    columns: [0]
                },
                beforeValidate(value, row, prop, source) {

                },
                licenseKey: 'non-commercial-and-evaluation'
            },
        }
    },
    methods: {
        logChange([row, col, oldval, newval]) {
            this.changes.push(`@afterChange [${row},${col},${oldval},${newval}]: Student ${this.$refs.spreadsheet.hotInstance.getDataAtCell(row, 0)}, Question ${col}, Mark ${newval}`);
        },
        getMaxMarkValidator(max_marks) {
            return (value, callback) => {
                callback(value <= max_marks);
            }
        },
        saveMark(changes, source) {
            if (source === "loadData")
                return;

            let marks = changes.map(([row, col, oldval, newval]) => ({
                student_upn: this.$refs.spreadsheet.hotInstance.getDataAtCell(row, 0),
                question_id: col,
                mark: newval
            }));

            // this.$inertia.patch(`/marks`, {marks});
            // need to extract CSRF token from cookie
            // TODO: move this to app.js and set it globally on an axios instance
            const csrfToken = this.getCookie('XSRF-TOKEN');

            axios.patch(`${this.$page.props.appUrl}/marks`, {marks}, { headers: {
                'X-XSRF-TOKEN': csrfToken
                }})
            .catch(err=>console.error(err))
            .then(res=>console.dir(res));
        },
        getCookie(cookie_name) {
            const cookies = document.cookie.split("; ");
            const cookie = cookies.find(c => c.startsWith(cookie_name));
            const startOfCookieValue = cookie.indexOf('=') + 1;
            return cookie.substring(startOfCookieValue);
        }
    },
}
</script>

<style>
    .console {
        background-color: black;
        width: 100%;
        height: 300px;
        overflow-y: scroll;
        color: white;
        font-family: "Fira Code", "Consolas", monospace;
    }

    .invalid-mark {
        background-color: rgb(255,180,180) !important;
        color: red;
    }
</style>
