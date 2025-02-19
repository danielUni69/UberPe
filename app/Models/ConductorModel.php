<?php

namespace App\Models;

use App\Core\Conductor;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConductorModel extends Model
{
    use HasFactory;

    protected $table = 'conductor'; // Nombre de la tabla

    protected $primaryKey = 'id_conductor'; // Clave primaria personalizada

    protected $fillable = [
        'persona_id', 'licencia', 'disponible',
    ];

    // Relación con PersonaModel
    public function persona()
    {
        return $this->belongsTo(PersonaModel::class, 'persona_id', 'id_persona');
    }

    // Relación con VehiculoModel
    public function vehiculos()
    {
        return $this->hasMany(VehiculoModel::class, 'conductor_id', 'id_conductor');
    }

    // Relación con ViajeModel
    public function viajes()
    {
        return $this->hasMany(ViajeModel::class, 'conductor_id', 'id_conductor');
    }

    // Relación con CalificacionConductorModel
    public function calificaciones()
    {
        return $this->hasMany(CalificacionConductorModel::class, 'conductor_id', 'id_conductor');
    }

    public function convertToConductor(): Conductor
    {
        $persona = $this->persona;

        if (! $persona) {
            throw new \Exception('No se encontró la persona asociada al conductor.');
        }

        return new Conductor(
            $persona->ci,
            $persona->nombres,
            $persona->apellidos,
            $persona->telefono,
            $persona->email,
            $persona->usuario,
            $persona->password,
            $persona->rol,
            $persona->billetera,
            $this->licencia,
            $this->disponible
        );
    }
}
