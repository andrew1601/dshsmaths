<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateStudentIdColumnOnStudentTeachingGroup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_teaching_group', function (Blueprint $table) {
            $table->renameColumn('student_id', 'student_upn');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_teaching_group', function (Blueprint $table) {
            $table->renameColumn('student_upn', 'student_id');
        });
    }
}
