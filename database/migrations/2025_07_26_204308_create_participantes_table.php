<?php
// database/migrations/2025_08_10_000000_create_participantes_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('participantes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_completo', 150)->nullable();
            $table->string('ci', 20);
            $table->date('fecha_nac')->nullable();
            $table->string('ci_exp', 2)->nullable();
            $table->string('celular', 20)->nullable();
            $table->enum('genero', ['Femenino','Masculino'])->nullable();
            $table->string('email', 120)->nullable();
            $table->string('provincia', 60)->nullable();
            $table->string('municipio', 100)->nullable();
            $table->enum('zona', ['Urbana','Rural'])->nullable();
            $table->string('direccion', 200)->nullable();
            $table->string('ocupacion', 120)->nullable();
            $table->string('organizacion', 120)->nullable();
            $table->text('observaciones')->nullable();
            $table->string('archivo', 255)->nullable();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->index(['ci']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('participantes');
    }
};
