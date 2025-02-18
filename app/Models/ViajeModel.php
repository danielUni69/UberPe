<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViajeModel extends Model
{
    use HasFactory;

    protected $table = 'viaje'; // Nombre de la tabla

    protected $primaryKey = 'id_viaje'; // Clave primaria personalizada

    protected $fillable = [
        'pasajero_id', 'conductor_id', 'origen', 'destino', 'fecha', 'metodo', 'estado', 'tarifa', 'saldo_bloqueado',
    ];

    // Relación con PersonaModel (Pasajero)
    public function pasajero()
    {
        return $this->belongsTo(PersonaModel::class, 'pasajero_id', 'id_persona');
    }

    // Relación con ConductorModel
    public function conductor()
    {
        return $this->belongsTo(ConductorModel::class, 'conductor_id', 'id_conductor');
    }

    // Relación con PagoModel
    public function pagos()
    {
        return $this->hasMany(PagoModel::class, 'viaje_id', 'id_viaje');
    }
}
