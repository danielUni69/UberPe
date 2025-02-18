<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PagoModel extends Model
{
    use HasFactory;

    protected $table = 'pago'; // Nombre de la tabla

    protected $primaryKey = 'id_pago'; // Clave primaria personalizada

    protected $fillable = [
        'viaje_id', 'monto_total', 'comision', 'monto_conductor', 'fecha',
    ];

    // Relación con ViajeModel
    public function viaje()
    {
        return $this->belongsTo(ViajeModel::class, 'viaje_id', 'id_viaje');
    }
}
