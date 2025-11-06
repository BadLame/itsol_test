<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    function up(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->foreignIdFor(User::class)->change()
                ->constrained()
                ->cascadeOnUpdate()
                ->nullOnDelete();
        });
    }

    function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->string('user_id')->comment('Автор')->change();
        });
    }
};
