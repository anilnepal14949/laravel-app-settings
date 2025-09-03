<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();               // dot.notation
            $table->longText('value')->nullable();         // JSON or scalar string
            $table->boolean('encrypted')->default(false);  // at-rest encryption flag
            $table->timestamps();
        });
    }
    public function down(): void {
        Schema::dropIfExists('settings');
    }
};
