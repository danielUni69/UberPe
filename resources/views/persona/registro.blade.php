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
                <form action="{{ route('persona.store') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Nombre(s)</label>
                        <input type="text" class="form-control" name="nombre" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Apellido(s)</label>
                        <input type="text" class="form-control" name="apellido" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Teléfono</label>
                        <input type="text" class="form-control" name="telefono" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Ciudad</label>
                        <input type="text" class="form-control" name="ciudad" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tipo</label>
                        <div>
                            <input type="radio" name="tipo" value="Pasajero" checked> Pasajero
                            <input type="radio" name="tipo" value="Conductor"> Conductor
                        </div>
                    </div>
                    <button type="submit" class="btn btn-custom w-100">Registrarse</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
