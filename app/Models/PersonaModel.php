<?php

namespace App\Models;

use App\Core\Persona;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class PersonaModel extends Authenticatable
{
    use HasFactory;

    protected $table = 'persona';

    protected $primaryKey = 'id_persona';

    protected $fillable = ['ci', 'nombres', 'apellidos', 'telefono', 'email', 'usuario', 'password', 'rol', 'billetera', 'deuda', 'foto']; // Añadir 'foto' a los fillables

    // Sobrescribe el nombre del campo de contraseña si es diferente
    public function getAuthPassword()
    {
        return $this->password;
    }

    // Método para convertir el modelo a una instancia de Persona
    public function convertToPersona()
    {
        return new Persona(
            $this->ci,
            $this->nombres,
            $this->apellidos,
            $this->telefono,
            $this->email,
            $this->usuario,
            $this->rol,
            $this->billetera,
            $this->deuda,
            $this->password,
            $this->foto 
        );
    }

    public function conductor()
    {
        return $this->hasOne(ConductorModel::class, 'persona_id');
    }

    public function sancion()
    {
        return $this->hasMany(SancionModel::class, 'persona_id');
    }
}
