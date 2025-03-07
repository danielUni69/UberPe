<?php

namespace App\Models;

use App\Core\Viaje;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ViajeModel extends Model
{
    use HasFactory;

    protected $table = 'viaje'; // Nombre de la tabla

    protected $primaryKey = 'id_viaje'; // Clave primaria personalizada

    protected $fillable = [
        'pasajero_id', 'conductor_id', 'origen', 'destino', 'fecha', 'metodo', 'estado', 'tarifa', 'saldo_bloqueado', 'descripcion',
    ];

    public $timestamps = true; // Habilitar campos created_at y updated_at

    public function convertToViaje(){
        return new Viaje($this->pasajero, $this->conductor, $this->origen, $this->destino, $this->fecha, $this->estado, $this->tarifa, $this->metodo, $this->saldo_bloqueado, $this->primaryKey);
    }
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
    public function persona()
    {
        return $this->belongsTo(PersonaModel::class, 'pasajero_id', 'id_persona');
    }
}
