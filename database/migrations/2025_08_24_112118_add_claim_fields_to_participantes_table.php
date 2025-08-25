<?php
// database/migrations/XXXX_XX_XX_XXXXXX_add_claim_fields_to_participantes_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('participantes', function (Blueprint $table) {
            $table->foreignId('claimed_by_user_id')->nullable()->constrained('users')->nullOnDelete()->after('user_id');
            $table->timestamp('claimed_at')->nullable()->after('claimed_by_user_id');
            $table->index(['claimed_by_user_id','claimed_at']);
        });
    }
    public function down(): void {
        Schema::table('participantes', function (Blueprint $table) {
            $table->dropConstrainedForeignId('claimed_by_user_id');
            $table->dropColumn('claimed_at');
        });
    }
};

