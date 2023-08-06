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
        Schema::table('users', function (Blueprint $table) {
            // Remove the 'approved' column if it exists
            if (Schema::hasColumn('users', 'verified')) {
                $table->dropColumn('verified');
            }

            // Add the 'status' column with default value 'pending'
            $table->string('status')->default('pending')->after('role');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            // Reverse the changes made in the 'up' method (if needed)
            $table->boolean('verified')->default(false);
            $table->dropColumn('status');
        });
    }
};
