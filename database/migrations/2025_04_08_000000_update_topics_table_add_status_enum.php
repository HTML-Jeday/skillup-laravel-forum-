<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use App\Enums\TopicStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('topics', function (Blueprint $table) {
            // Add the new status column
            $table->integer('status')->after('text')->default(TopicStatus::OPENED->value);
        });

        // Convert existing opened boolean values to the new status enum
        DB::statement('UPDATE topics SET status = opened');

        Schema::table('topics', function (Blueprint $table) {
            // Remove the old opened column
            $table->dropColumn('opened');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('topics', function (Blueprint $table) {
            // Add the old opened column back
            $table->boolean('opened')->after('text')->default(true);
        });

        // Convert the status enum values back to boolean
        DB::statement('UPDATE topics SET opened = status');

        Schema::table('topics', function (Blueprint $table) {
            // Remove the new status column
            $table->dropColumn('status');
        });
    }
};
