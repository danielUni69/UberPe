<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #5b8c2a;
        }
        .container-form {
            background-color: #b6ff80;
            padding: 50px;
            border-radius: 10px;
            max-width: 800px;
            margin: auto;
        }
        .btn-custom {
            background-color: #243b0b;
            color: white;
        }
        .btn-custom:hover {
            background-color: #1a2a07;
        }
        h2 {
            text-align: center;
            font-weight: bold;
            color: white;
        }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h2>Reporte</h2>
        <div class="container-form">
            <form>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Pasajero</label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Origen</label>
                        <input type="text" class="form-control">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Conductor</label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Estado</label>
                        <input type="text" class="form-control">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Vehículo</label>
                        <input type="email" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Monto</label>
                        <input type="text" class="form-control">
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Destino</label>
                        <input type="text" class="form-control">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label">Tipo de Pago</label>
                        <input type="text" class="form-control">
                    </div>
                </div>
                <div class="text-end">
                    <button type="submit" class="btn btn-custom">Enviar</button>
                </div>
            </form>
        </div>
        <div class="mt-3">
            <button type="button" class="btn btn-custom">Atrás</button>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>