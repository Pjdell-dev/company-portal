<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateSubmodulesTable extends Migration
{
    public function up()
    {
        Schema::create('submodules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('code')->unique();
            $table->string('route')->nullable(); // e.g., '/users'
            $table->timestamps();
        });

        $userMgmtId = DB::table('modules')->where('code', 'USER_MGMT')->first()->id;
        $roleMgmtId = DB::table('modules')->where('code', 'ROLE_MGMT')->first()->id;
        $auditLogsId = DB::table('modules')->where('code', 'AUDIT_LOGS')->first()->id;
        $billingId = DB::table('modules')->where('code', 'BILLING')->first()->id;
        $invoicingId = DB::table('modules')->where('code', 'INVOICING')->first()->id;
        $reportsId = DB::table('modules')->where('code', 'REPORTS')->first()->id;

        DB::table('submodules')->insert([
            ['module_id' => $userMgmtId, 'name' => 'Users', 'code' => 'USERS', 'route' => '/users', 'created_at' => now(), 'updated_at' => now()],
            ['module_id' => $userMgmtId, 'name' => 'Groups', 'code' => 'GROUPS', 'route' => '/groups', 'created_at' => now(), 'updated_at' => now()],
            ['module_id' => $roleMgmtId, 'name' => 'Roles', 'code' => 'ROLES', 'route' => '/roles', 'created_at' => now(), 'updated_at' => now()],
            ['module_id' => $roleMgmtId, 'name' => 'Permissions', 'code' => 'PERMISSIONS', 'route' => '/permissions', 'created_at' => now(), 'updated_at' => now()],
            ['module_id' => $auditLogsId, 'name' => 'Activity Log', 'code' => 'ACTIVITY_LOG', 'route' => '/activity', 'created_at' => now(), 'updated_at' => now()],
            ['module_id' => $billingId, 'name' => 'Invoices', 'code' => 'INVOICES', 'route' => '/invoices', 'created_at' => now(), 'updated_at' => now()],
            ['module_id' => $invoicingId, 'name' => 'Generate Invoice', 'code' => 'GENERATE_INVOICE', 'route' => '/generate-invoice', 'created_at' => now(), 'updated_at' => now()],
            ['module_id' => $reportsId, 'name' => 'Financial Summary', 'code' => 'FIN_SUMMARY', 'route' => '/summary', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('submodules');
    }
}