<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBaselinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baselines', function (Blueprint $table) {
            $table->integer('id')->unsigned()->primary();
            $table->integer('assessment_source_id')->unsigned();
            $table->string('student_id');
            $table->string('grade')->nullable(true);
            $table->timestamps();

            $table->foreign('assessment_source_id')->references('id')->on('assessment_sources')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('student_id')->references('upn')->on('students')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('baselines');
    }
}
