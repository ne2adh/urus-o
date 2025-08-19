<?php
// database/migrations/2025_08_11_000000_create_resumen_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('resumen', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->date('fecha');
            $table->unsignedSmallInteger('numero_dia');

            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            $table->string('provincia', 100);
            $table->string('municipio', 100);
            $table->unsignedSmallInteger('circunscripcion');

            // Métricas del día
            $table->unsignedInteger('total_dia');
            $table->unsignedInteger('total_dia_prov');
            $table->unsignedInteger('total_dia_mun');
            $table->unsignedInteger('total_dia_circ');

            // Acumulados
            $table->unsignedInteger('acum_user');
            $table->unsignedInteger('acum_user_prov')->default(0);
            $table->unsignedInteger('acum_user_mun')->default(0);
            $table->unsignedInteger('acum_user_circ')->default(0);
            $table->decimal('porc_meta_user', 5, 2)->nullable();

            // Auditoría
            $table->foreignId('created_by')->nullable()->constrained('users');
            $table->foreignId('updated_by')->nullable()->constrained('users');

            $table->timestamps();

            $table->unique([
                'fecha', 'numero_dia', 'user_id', 'provincia', 'municipio', 'circunscripcion'
            ], 'uq_resumen_dia_user_geo');

            $table->index('fecha');
            $table->index('numero_dia');
            $table->index(['user_id', 'fecha']);
            $table->index(['provincia', 'municipio', 'fecha'], 'idx_geo_fecha');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('resumen');
    }
};

