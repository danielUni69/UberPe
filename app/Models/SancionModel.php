<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SancionModel extends Model
{
    use HasFactory;

    protected $table = 'sancion'; // Nombre de la tabla

    protected $primaryKey = 'id_sancion'; // Clave primaria personalizada

    protected $fillable = [
        'persona_id', 'reclamo_id', 'motivo', 'tipo', 'fecha_inicio', 'fecha_fin', 'estado',
    ];

    // Relación con PersonaModel
    public function persona()
    {
        return $this->belongsTo(PersonaModel::class, 'persona_id', 'id_persona');
    }

    // Relación con ReclamoModel
    public function reclamo()
    {
        return $this->belongsTo(ReclamoModel::class, 'reclamo_id', 'id_reclamo');
    }
}
