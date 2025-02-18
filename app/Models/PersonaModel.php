<?php

namespace App\Models;

use App\Core\Persona;
use Illuminate\Database\Eloquent\Model;

class PersonaModel extends Model
{
    protected $table = 'persona';

    protected $primaryKey = 'id_persona';

    protected $fillable = ['ci', 'nombres', 'apellidos', 'telefono', 'email', 'usuario', 'password', 'rol', 'billetera', 'deuda'];

    public function convertToPersona(): Persona
    {
        return new Persona(
            $this->ci,
            $this->nombres,
            $this->apellidos,
            $this->telefono,
            $this->email,
            $this->usuario,
            $this->password,
            $this->rol,
            $this->billetera,
            $this->deuda
        );
    }
}
