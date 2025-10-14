<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class CreateUsersTable extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('email')->nullable()->unique();
            $table->string('password');
            $table->string('full_name');
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Insert sample users
        $acmeId = DB::table('companies')->where('code', 'ACME')->first()->id;
        $betaId = DB::table('companies')->where('code', 'BETA')->first()->id;
        $gammaId = DB::table('companies')->where('code', 'GAMMA')->first()->id;

        DB::table('users')->insert([
            [
                'username' => 'alice',
                'email' => 'alice@example.com',
                'password' => Hash::make('Passw0rd!'),
                'full_name' => 'Alice Smith',
                'company_id' => $acmeId,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'bob',
                'email' => 'bob@example.com',
                'password' => Hash::make('Passw0rd!'),
                'full_name' => 'Bob Lee',
                'company_id' => $betaId,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'charlie',
                'email' => 'charlie@example.com',
                'password' => Hash::make('Passw0rd!'),
                'full_name' => 'Charlie Brown',
                'company_id' => $gammaId,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'diana',
                'email' => 'diana@example.com',
                'password' => Hash::make('Passw0rd!'),
                'full_name' => 'Diana Prince',
                'company_id' => $acmeId,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'edward',
                'email' => 'edward@example.com',
                'password' => Hash::make('Passw0rd!'),
                'full_name' => 'Edward Norton',
                'company_id' => $betaId,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'username' => 'fiona',
                'email' => 'fiona@example.com',
                'password' => Hash::make('Passw0rd!'),
                'full_name' => 'Fiona Apple',
                'company_id' => $gammaId,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}