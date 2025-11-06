<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    function up(): void
    {
        Schema::whenTableDoesntHaveColumn(
            'media',
            'alt',
            function (Blueprint $table) {
                $table->text('alt')->nullable();
            }
        );
    }

    function down(): void
    {
        Schema::whenTableHasColumn(
            'media',
            'alt',
            function (Blueprint $table) {
                $table->dropColumn('alt');
            }
        );
    }

    function getConnection()
    {
        return config('mediable.connection_name', parent::getConnection());
    }
};
