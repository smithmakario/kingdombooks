<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->after('id');
            $table->string('last_name')->nullable()->after('first_name');
            $table->string('phone', 30)->nullable()->after('email');
            $table->boolean('is_superadmin')->default(false)->after('remember_token');
            $table->boolean('is_approved')->default(false)->after('is_superadmin');
        });

        DB::table('users')->orderBy('id')->get(['id', 'name'])->each(function (object $user): void {
            $name = trim((string) $user->name);
            $nameParts = preg_split('/\s+/', $name, 2) ?: [];
            $firstName = $nameParts[0] ?? 'User';
            $lastName = $nameParts[1] ?? null;

            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('name')->nullable()->after('id');
        });

        DB::table('users')->orderBy('id')->get(['id', 'first_name', 'last_name'])->each(function (object $user): void {
            $fullName = trim(implode(' ', array_filter([(string) $user->first_name, (string) $user->last_name])));

            DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'name' => $fullName !== '' ? $fullName : 'User',
                ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'first_name',
                'last_name',
                'phone',
                'is_superadmin',
                'is_approved',
            ]);
        });
    }
};
