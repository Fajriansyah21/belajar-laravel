<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->string('username', 100)->nullable()->unique()->after('name');
        });

        // Isi username untuk akun lama agar tetap bisa dipakai setelah migration.
        $users = DB::table('users')
            ->select('id', 'name')
            ->whereNull('username')
            ->orderBy('id')
            ->get();

        foreach ($users as $user) {
            $base = Str::slug((string) $user->name);
            $username = substr($base !== '' ? $base : 'user', 0, 85) . '-' . $user->id;

            DB::table('users')->where('id', $user->id)->update([
                'username' => $username,
            ]);
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropUnique(['username']);
            $table->dropColumn('username');
        });
    }
};
