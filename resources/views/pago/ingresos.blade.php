<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ingresos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #5f8800;
            text-align: center;
        }
        .card {
            background-color: #caff7a;
            padding: 60px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .balance {
            font-size: 2rem;
            font-weight: bold;
        }
        .message {
            background-color: #333;
            color: white;
            padding: 10px;
            border-radius: 5px;
            display: inline-block;
            position: absolute;
            top: 20px;
            left: 20px;
        }
        .btn-recargar {
            background-color: #222;
            color: white;
            border: none;
        }
        .btn-recargar:hover {
            background-color: #444;
        }
        .title {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            color: white;
            font-size: 2rem;
            font-weight: bold;
        }
        .saldo-title {
            color: white;
        }
    </style>
</head>
<body>
    <div class="title">Ingresos</div>
    <div class="container d-flex flex-column align-items-center justify-content-center vh-100">
        <div class="message">Debe recargar una cuota para activar</div>
        <div class="card text-center">
            <h2 class="fw-bold saldo-title">Saldo</h2>
            <div class="balance">50.00 Bs</div>
            <button class="btn btn-recargar mt-3 px-4 py-2">Recargar</button>
        </div>
    </div>
</body>
</html>