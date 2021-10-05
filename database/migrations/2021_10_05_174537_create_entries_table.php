<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::create('phonebook_entries', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid');
            $table->string('first_name');
            $table->string('middle_names')->nullable();
            $table->string('last_name');
            $table->string('email_address');
            $table->string('landline_number')->nullable();
            $table->string('mobile_number');
//            $table->string('primary_number', 31);
            $table->unsignedBigInteger('phonebook_id');
            $table->foreign('phonebook_id')->references('id')->on('phonebooks');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('phonebook_entries');
    }
};
