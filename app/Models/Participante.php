<?php
// app/Models/Participante.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Participante extends Model
{
    use HasFactory;

    protected $table = 'participantes';

    protected $fillable = [
        'nombre_completo','ci','celular','ci_exp','fecha_nac','genero','email',
        'provincia','municipio','zona','direccion','ocupacion','organizacion',
        'observaciones','user_id','archivo','claimed_by_user_id','claimed_at'
    ];

    protected $casts = [
        'fecha_nac' => 'date:Y-m-d',
    ];

    public function claimedBy()
    {
        return $this->belongsTo(User::class, 'claimed_by_user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
