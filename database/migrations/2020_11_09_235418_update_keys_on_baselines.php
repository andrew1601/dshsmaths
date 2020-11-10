<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateKeysOnBaselines extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('baselines', function (Blueprint $table) {
           $table->string('student_upn')->nullable()->change();
           $table->integer('assessment_source_id')->unsigned()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('baselines', function (Blueprint $table) {
            $table->string('student_upn')->nullable(false)->change();
            $table->integer('assessment_source_id')->unsigned()->nullable(false)->change();
        });
    }
}
