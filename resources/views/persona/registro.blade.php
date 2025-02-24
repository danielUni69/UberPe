<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Persona</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
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
    </style>
</head>

<body>
    <div class="container d-flex justify-content-center">
        <div class="col-md-6">
            <div class="form-container">
                <h2 class="text-center">Registro</h2>
                <form action="{{ route('registro') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nombre(s)</label>
                        <input type="text" class="form-control @error('nombres') is-invalid @enderror" name="nombres"
                            value="{{ old('nombres') }}">
                        @error('nombres')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Apellido(s)</label>
                        <input type="text" class="form-control @error('apellidos') is-invalid @enderror"
                            name="apellidos" value="{{ old('apellidos') }}">
                        @error('apellidos')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">CI</label>
                        <input type="text" class="form-control @error('ci') is-invalid @enderror" name="ci"
                            value="{{ old('ci') }}">
                        @error('ci')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>


                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                            value="{{ old('email') }}">
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text" class="form-control @error('telefono') is-invalid @enderror"
                            name="telefono" value="{{ old('telefono') }}">
                        @error('telefono')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Usuario</label>
                        <input type="text" class="form-control @error('usuario') is-invalid @enderror" name="usuario"
                            value="{{ old('usuario') }}">
                        @error('usuario')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror"
                            name="password">
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Billetera</label>
                        <input type="number" class="form-control @error('billetera') is-invalid @enderror"
                            name="billetera" value="{{ old('billetera') }}">
                        @error('billetera')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Tipo</label>
                        <div>
                            <input type="radio" name="rol" value="Pasajero"
                                {{ old('rol') == 'Pasajero' ? 'checked' : '' }}> Pasajero
                            <input type="radio" name="rol" value="Conductor"
                                {{ old('rol') == 'Conductor' ? 'checked' : '' }}> Conductor
                        </div>
                        @error('rol')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-custom w-100">Registrarse</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>
