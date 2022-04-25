<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            if (config('permissible.first_last_name_migration', false) === true) {
                $hasFirstNameColumn = Schema::hasColumn('users', 'first_name');
                $hasLastNameColumn = Schema::hasColumn('users', 'last_name');
                $hasNameColumn = Schema::hasColumn('users', 'name');
                $hasDeletedAtColumn = Schema::hasColumn('users', 'deleted_at');

                if ($hasNameColumn) {
                    $table->dropColumn('name');
                }

                if (!$hasLastNameColumn) {
                    $table->string('last_name')->after('id');
                }

                if (!$hasFirstNameColumn) {
                    $table->string('first_name')->after('id');
                }

                if (!$hasDeletedAtColumn) {
                    $table->softDeletes();
                }
            }
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (config('permissible.first_last_name_migration', false) === true) {
            Schema::table('users', function (Blueprint $table) {

                $hasFirstNameColumn = Schema::hasColumn('users', 'first_name');
                $hasLastNameColumn = Schema::hasColumn('users', 'last_name');
                $hasNameColumn = Schema::hasColumn('users', 'name');

                if (!$hasNameColumn) {
                    $table->string('name')->after('id');
                }

                if ($hasLastNameColumn) {
                    $table->dropColumn('last_name');
                }

                if ($hasFirstNameColumn) {
                    $table->dropColumn('first_name');
                }

            });
        }
    }
};
