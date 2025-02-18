<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración de Conductor</title>
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
            text-align: left;
        }
        .title {
            color: white;
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }
        .profile-icon {
            width: 80px;
            height: 80px;
            background-color: #d1c4fc;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            margin: 0 auto 20px;
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
        <h2 class="title">Configuración de Conductor</h2>
        <div class="profile-icon">👤</div>
        <div class="container-box">
            <form>
                <div class="mb-3">
                    <label class="form-label">Nombre(s)</label>
                    <input type="text" class="form-control" value="Jose" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Apellido(s)</label>
                    <input type="text" class="form-control" value="Basilio" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="text" class="form-control" value="josebasilio@gmail.com" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Teléfono</label>
                    <input type="text" class="form-control" value="51586484" readonly>
                </div>
                <div class="mb-3">
                    <label class="form-label">Ciudad</label>
                    <input type="text" class="form-control" value="Potosí" readonly>
                </div>
                <button type="submit" class="btn btn-submit">Guardar</button>
            </form>
        </div>
    </div>

</body>
</html>
