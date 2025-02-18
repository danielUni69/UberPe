<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Incidencias</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #5d8800; /* Verde de fondo */
        }
        .container-box {
            background-color: #c8ff8e; /* Verde claro */
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.2);
            width: 400px;
        }
        .title {
            color: white;
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }
        .btn-submit {
            background-color: black;
            color: white;
            width: 100%;
        }
    </style>
</head>
<body>

    <div class="d-flex flex-column align-items-center vh-100 justify-content-center">
        <h2 class="title">Incidencias</h2>
        <div class="container-box">
            <form>
                <div class="mb-3">
                    <label class="form-label">Calificación</label>
                    <div>
                        ⭐⭐⭐⭐⭐
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label">Comentario</label>
                    <input type="text" class="form-control" value="Basilio" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Fecha</label>
                    <input type="email" class="form-control" value="josebasilio@gmail.com" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Pasajero</label>
                    <input type="text" class="form-control" value="51586484" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Conductor</label>
                    <input type="text" class="form-control" value="Potosí" readonly>
                </div>
                <button type="submit" class="btn btn-submit">Enviar</button>
            </form>
        </div>
    </div>

</body>
</html>
