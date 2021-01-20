<!DOCTYPE html>
<html>
    <head>
        <title>DSHS Maths Test Analysis</title>
        <style>
            * {
                box-sizing: border-box;
            }

            .row {
                width: 100%;
            }

            .row div {
                display: inline-block;
            }

            .overview_data {
                width: 100%;
                border: 1px black solid;
                padding: 0;
            }

            .overview_data tr, .overview_data td {
                border: 1px black solid;
            }

            .overview_data__label {
                background-color: #ababab;
                border: 1px black solid;
            }

            .paper_analysis {
                width: 100%;
                border: 1px black solid;
                padding: 0;
                page-break-after: always;
            }

            .paper_analysis__thead__th {
                background-color: black;
                color: white;
            }

            .paper_analysis tr, .paper_analysis td, .paper_analysis th {
                border: 1px solid black;
            }
        </style>
    </head>
    <body>
{{--    {{ dd($analysis) }}--}}
        <div class="row">
            <div style="width: 33%;"><img width="100%" src="{{ public_path('/img/dshs_badge_and_text_black.png') }}"></div>
            <div style="width: 66%; text-align: center;"><h4>DSHS Test Analysis</h4><p>{{ $analysis['test']->name }}</p></div>
        </div>

        <div class="row" style="margin-top: 20px;">
            <div style="width: 66%;">
                <table class="overview_data">
                    <tr>
                        <th class="overview_data__label">Name</th>
                        <td class="overview_data__value">{{ $analysis['student']->first_name }} {{ $analysis['student']->last_name }}</td>
                    </tr>
                    <tr>
                        <th class="overview_data__label">IGR</th>
                        <td class="overview_data__value">{{ $analysis['student']->baseline->grade }}</td>
                    </tr>
                </table>
            </div>
            <div style="width: 33%;">
                <table class="overview_data">
                    <tr>
                        <th class="overview_data__label">Marks</th>
                        <td class="overview_data__value">{{ $analysis['total_marks'] }}</td>
                    </tr>
                    <tr>
                        <th class="overview_data__label">Grade</th>
                        <td class="overview_data__value">{{ $analysis['grade'] }}</td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="row">
            <div style="width: 100%;">
                @foreach($analysis['test']->papers as $paper)
                <h4>{{ $paper->name }}</h4>
                <table class="paper_analysis">
                    <thead class="paper_analysis__thead">
                        <tr>
                            <th class="paper_analysis__thead__th">Question</th>
                            <th class="paper_analysis__thead__th">Area</th>
                            <th class="paper_analysis__thead__th">Topic</th>
                            <th class="paper_analysis__thead__th">Max Marks</th>
                            <th class="paper_analysis__thead__th">Your Marks</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($paper->questions as $question)
                            <tr class="paper_analysis__question">
                                <td class="paper_analysis__question__number">{{ $question->number }}</td>
                                <td class="paper_analysis__question__area">{{ $question->area }}</td>
                                <td class="paper_analysis__question__topic">{{ $question->topic }}</td>
                                <td class="paper_analysis__question__max_marks">{{ $question->marks }}</td>
                                <td class="paper_analysis__question__your_marks"></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @endforeach
            </div>
        </div>
    </body>
</html>
