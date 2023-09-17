<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('registers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('icon');
            $table->timestamps();
            $table->unique('name');
        });

        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('company_name');
            $table->string('trade_name');
            $table->enum('person_type', ['natural', 'legal']);
            $table->string('document');
            $table->string('state');
            $table->string('city');
            $table->string('email');
            $table->string('phone');
            $table->string('address', 500);
            $table->enum('status', ['active', 'inactive', 'prospect']);
            $table->timestamps();

            $table->unique('document');
        });

        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            $table->string('name');
            $table->string('email');
            $table->string('phone');
            $table->string('function');
            $table->timestamps();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
        });

        Schema::create('forms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('register_id');
            $table->enum('type', ['form', 'list', 'files']);
            $table->timestamps();
            $table->foreign('register_id')->references('id')->on('registers')->onDelete('cascade');
        });

        Schema::create('fields', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('label');
            $table->enum('type', ['text', 'select', 'number', 'email']);
            $table->unsignedBigInteger('form_id');
            $table->foreign('form_id')->references('id')->on('forms')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('options', function (Blueprint $table) {
            $table->id();
            $table->string('value');
            $table->string('text');
            $table->unsignedBigInteger('field_id');
            $table->foreign('field_id')->references('id')->on('fields')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('validations', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['required', 'max', 'min', 'unique', 'phone', 'doc']);
            $table->unsignedBigInteger('field_id');
            $table->foreign('field_id')->references('id')->on('fields')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('customers_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('customer_id');
            // $table->string('path')->nullable();
            $table->longText('file')->nullable();
            $table->enum('type', ['folder', 'file']);
            $table->timestamps();

            $table->unsignedBigInteger('customers_file_id')->nullable();
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('cascade');
            $table->foreign('customers_file_id')->references('id')->on('customers_files')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::drop('customers');
        Schema::drop('contacts');
        Schema::drop('fields');
        Schema::drop('options');
        Schema::drop('validations');
        Schema::drop('customers_files');
    }
};
