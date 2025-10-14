<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateModulesTable extends Migration
{
    public function up()
    {
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('system_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('code')->unique();
            $table->string('icon')->nullable();
            $table->timestamps();
        });

        $adminId = DB::table('systems')->where('code', 'ADMIN')->first()->id;
        $financeId = DB::table('systems')->where('code', 'FIN')->first()->id;

        DB::table('modules')->insert([
            ['system_id' => $adminId, 'name' => 'User Management', 'code' => 'USER_MGMT', 'icon' => 'user', 'created_at' => now(), 'updated_at' => now()],
            ['system_id' => $adminId, 'name' => 'Role Management', 'code' => 'ROLE_MGMT', 'icon' => 'shield', 'created_at' => now(), 'updated_at' => now()],
            ['system_id' => $adminId, 'name' => 'Audit Logs', 'code' => 'AUDIT_LOGS', 'icon' => 'file', 'created_at' => now(), 'updated_at' => now()],
            ['system_id' => $financeId, 'name' => 'Billing', 'code' => 'BILLING', 'icon' => 'dollar', 'created_at' => now(), 'updated_at' => now()],
            ['system_id' => $financeId, 'name' => 'Invoicing', 'code' => 'INVOICING', 'icon' => 'receipt', 'created_at' => now(), 'updated_at' => now()],
            ['system_id' => $financeId, 'name' => 'Reports', 'code' => 'REPORTS', 'icon' => 'chart', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('modules');
    }
}