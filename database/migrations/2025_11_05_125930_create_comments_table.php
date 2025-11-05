<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    function up(): void
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->morphs('commentable');
            $table->foreignIdFor(User::class)
                ->index()
                ->constrained()
                ->cascadeOnUpdate()
                ->nullOnDelete();
            $table->text('content');
            $table->timestamps();
        });
    }

    function down(): void
    {
        Schema::dropIfExists('comments');
    }
};
