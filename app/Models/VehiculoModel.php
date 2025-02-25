<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VehiculoModel extends Model
{
    use HasFactory;

    protected $table = 'vehiculo'; // Nombre de la tabla

    protected $primaryKey = 'id_vehiculo'; // Clave primaria personalizada

    protected $fillable = [
        'conductor_id', 'marca', 'modelo', 'placa', 'color', 'foto',
    ];

    // Relación con ConductorModel
    public function conductor()
    {
        return $this->belongsTo(ConductorModel::class, 'conductor_id', 'id_conductor');
    }
}
