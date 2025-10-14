<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateSystemsTable extends Migration
{
    public function up()
    {
        Schema::create('systems', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('code')->unique();
            $table->timestamps();
        });

        DB::table('systems')->insert([
            ['name' => 'Admin', 'code' => 'ADMIN', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Finance', 'code' => 'FIN', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('systems');
    }
}