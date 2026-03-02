<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique()->comment('Material key matching calculator services, e.g. profile_cd');
            $table->string('name')->comment('Display name for UI');
            $table->string('search_term')->nullable()->comment('Simplified name for store search engines');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
