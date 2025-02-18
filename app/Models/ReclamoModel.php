<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SancionModel;

class ReclamoModel extends Model
{
    use HasFactory;

    protected $table = 'reclamo'; // Nombre de la tabla

    protected $primaryKey = 'id_reclamo'; // Clave primaria personalizada

    protected $fillable = [
        'persona_id', 'motivo', 'fecha',
    ];

    // Relación con PersonaModel
    public function persona()
    {
        return $this->belongsTo(PersonaModel::class, 'persona_id', 'id_persona');
    }

    // Relación con SancionModel
    public function sanciones()
    {
        return $this->hasMany(SancionModel::class, 'reclamo_id', 'id_reclamo');
    }
}
