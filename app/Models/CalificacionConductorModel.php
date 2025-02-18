<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CalificacionConductorModel extends Model
{
    use HasFactory;

    protected $table = 'calificacion_conductor'; // Nombre de la tabla

    protected $primaryKey = 'id_calificacion'; // Clave primaria personalizada

    protected $fillable = [
        'conductor_id', 'calificacion', 'fecha',
    ];

    // Relación con ConductorModel
    public function conductor()
    {
        return $this->belongsTo(ConductorModel::class, 'conductor_id', 'id_conductor');
    }
}
