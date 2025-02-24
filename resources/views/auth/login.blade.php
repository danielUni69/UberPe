<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/@tailwindcss/browser@4"></script>
    <style>
        body {
            background: linear-gradient(to right, #a3e635, #1e3a08);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-container {
            max-width: 400px;
            margin: 50px auto;
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0px 0px 50px;
            text-align: center;
            position: relative;
        }

        .icon-container {
            width: 100px;
            height: 100px;
            background: #28a745;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: -60px auto 20px auto;
        }

        .icon-container img {
            width: 80px;
            height: 80px;
            border-radius: 50%;
        }

        .form-group {
            background: #80c90b;
            padding: 10px;
            border-radius: 5px;
            text-align: left;
            margin-top: 10px;
        }

        .form-control {
            background: #e6f0ff;
        }

        .btn-success {
            background-color: #609708;
            border: none;
            height: 40px;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="login-container">
            <div class="icon-container">
                <img src="https://img.freepik.com/fotos-premium/icono-perfil-fondo-blanco_941097-162260.jpg"
                    alt="User Icon">
            </div>
            <form action="{{ route('login') }}" method="POST">
                @csrf
                <div class="mb-3 form-group">
                    <label for="usuario" class="form-label text-white">Usuario</label>
                    <input type="text" class="form-control" id="usuario" name="usuario" required>
                </div>
                <div class="mb-3 form-group">
                    <label for="password" class="form-label text-white">Contraseña</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <button type="submit" class="btn btn-success w-100 mt-2">ENTRAR</button>
            </form>
            <div class="mt-3 flex">
                <a href="{{ route('conductor.registro') }}" class="text-success">Registrarse como conductor</a>
                <a href="{{ route('registro') }}" class="text-success">Registrarse como pasajero</a>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
