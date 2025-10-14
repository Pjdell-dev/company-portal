<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateCompaniesTable extends Migration
{
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->string('primary_color'); // e.g., #3490dc
            $table->string('accent_color')->nullable();
            $table->string('logo_url')->nullable();
            $table->timestamps();
        });

        // Insert sample companies
        DB::table('companies')->insert([
            [
                'name' => 'ACME Corp',
                'code' => 'ACME',
                'primary_color' => '#3490dc',
                'accent_color' => '#ffcc00',
                'logo_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'BETA Ltd',
                'code' => 'BETA',
                'primary_color' => '#e3342f',
                'accent_color' => '#38c172',
                'logo_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'GAMMA Inc',
                'code' => 'GAMMA',
                'primary_color' => '#657b83',
                'accent_color' => '#fdf6e3',
                'logo_url' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('companies');
    }
}