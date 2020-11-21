<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSubjectNameAndSubjectCodeToAssessmentSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('assessment_sources', function (Blueprint $table) {
            $table->string('subject_name')->nullable();
            $table->string('subject_code')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('assessment_sources', function (Blueprint $table) {
            $table->dropColumn(['subject_name', 'subject_code']);
        });
    }
}
