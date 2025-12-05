<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up(): void
    {
        Schema::create('test_run', function(Blueprint $table){
            $table->id();
            $table->string('username')->nullable();
            $table->integer('phpunit_exit');
            $table->string('project_code');
            $table->string('report_url');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('test_run');
    }
};
