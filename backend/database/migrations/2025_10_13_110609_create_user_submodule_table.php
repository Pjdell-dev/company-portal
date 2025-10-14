<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CreateUserSubmoduleTable extends Migration
{
    public function up()
    {
        Schema::create('user_submodule', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('submodule_id')->constrained()->onDelete('cascade');
            $table->timestamp('granted_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->timestamps();
        });

        // Assign permissions to users
        $aliceId = DB::table('users')->where('username', 'alice')->first()->id;
        $bobId = DB::table('users')->where('username', 'bob')->first()->id;
        $charlieId = DB::table('users')->where('username', 'charlie')->first()->id;

        $usersSubId = DB::table('submodules')->where('code', 'USERS')->first()->id;
        $groupsSubId = DB::table('submodules')->where('code', 'GROUPS')->first()->id;
        $rolesSubId = DB::table('submodules')->where('code', 'ROLES')->first()->id;
        $invoicesSubId = DB::table('submodules')->where('code', 'INVOICES')->first()->id;
        $generateInvoiceSubId = DB::table('submodules')->where('code', 'GENERATE_INVOICE')->first()->id;
        $finSummarySubId = DB::table('submodules')->where('code', 'FIN_SUMMARY')->first()->id;

        DB::table('user_submodule')->insert([
            ['user_id' => $aliceId, 'submodule_id' => $usersSubId, 'granted_at' => now(), 'created_by' => $aliceId, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => $aliceId, 'submodule_id' => $groupsSubId, 'granted_at' => now(), 'created_by' => $aliceId, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => $aliceId, 'submodule_id' => $rolesSubId, 'granted_at' => now(), 'created_by' => $aliceId, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => $bobId, 'submodule_id' => $invoicesSubId, 'granted_at' => now(), 'created_by' => $bobId, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => $bobId, 'submodule_id' => $generateInvoiceSubId, 'granted_at' => now(), 'created_by' => $bobId, 'created_at' => now(), 'updated_at' => now()],
            ['user_id' => $charlieId, 'submodule_id' => $finSummarySubId, 'granted_at' => now(), 'created_by' => $charlieId, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('user_submodule');
    }
}