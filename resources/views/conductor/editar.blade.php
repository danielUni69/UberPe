@extends('layouts.layout')

@section('title', 'Editar Perfil')

@section('content')
<style>
        body {
            background-color: #5b8c1a;
        }

        .container {
            margin-top: 50px;
        }

        .form-container {
            background-color: #c5ff7d;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .btn-custom {
            background-color: black;
            color: white;
        }

        .btn-secondary {
            background-color: #6c757d;
            color: white;
        }
    </style>
</head>

<body>
    <div class="container d-flex justify-content-center">
        <div class="col-md-10">
            <div class="form-container">
                <h2 class="text-center">Editar Perfil</h2>
                <form action="{{ route('conductor.editar') }}" method="POST">
                    @csrf
                    <div class="row">
                        <!-- Primera columna -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Nombre(s)</label>
                                <input type="text" class="form-control @error('nombres') is-invalid @enderror"
                                    name="nombres" value="{{ old('nombres', $persona->getNombres()) }}">
                                @error('nombres')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Apellido(s)</label>
                                <input type="text" class="form-control @error('apellidos') is-invalid @enderror"
                                    name="apellidos" value="{{ old('apellidos', $persona->getApellidos()) }}">
                                @error('apellidos')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">CI</label>
                                <input type="text" class="form-control @error('ci') is-invalid @enderror"
                                    name="ci" value="{{ old('ci', $persona->getCi()) }}">
                                @error('ci')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email', $persona->getEmail()) }}">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <!-- Segunda columna -->
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label">Teléfono</label>
                                <input type="text" class="form-control @error('telefono') is-invalid @enderror"
                                    name="telefono" value="{{ old('telefono', $persona->getTelefono()) }}">
                                @error('telefono')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Usuario</label>
                                <input type="text" class="form-control @error('usuario') is-invalid @enderror"
                                    name="usuario" value="{{ old('usuario', $persona->getUsuario()) }}">
                                @error('usuario')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Billetera</label>
                                <input type="number" class="form-control @error('billetera') is-invalid @enderror"
                                    name="billetera" value="{{ old('billetera', $persona->getBilletera()) }}">
                                @error('billetera')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Campos específicos de conductor -->
                            @if (Auth::user()->rol === 'Conductor')
                                <div class="mb-3 conductor-fields">
                                    <label class="form-label">Número de Licencia</label>
                                    <input type="text" class="form-control @error('licencia') is-invalid @enderror"
                                        name="licencia" value="{{ old('licencia', $conductor->getLicencia()) }}">
                                    @error('licencia')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <input type="hidden" name="disponible" value="0">
                                <input class="form-check-input" type="checkbox" name="disponible"
                                id="disponible" value="1"
                                {{ old('disponible', $conductor->getDisponible()) ? 'checked' : '' }}>

                            @endif
                        </div>
                    </div>

                    <div class="row mt-3">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-custom w-100">Guardar Cambios</button>
                        </div>
                        <div class="col-md-6">
                            <a href="{{ route('home') }}" class="btn btn-secondary w-100">Cancelar</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
@endsection