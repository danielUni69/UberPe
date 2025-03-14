<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.materialdesignicons.com/6.5.95/css/materialdesignicons.min.css" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.tailwindcss.com"></script>

     <style>
        /* Estilos personalizados */
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }

        .sidebar {
            width: 250px;
            background-color: #343a40;
            color: white;
            padding: 20px;
            height: 100vh;
            /* Ocupa toda la altura de la pantalla */
            position: fixed;
            /* Fija el sidebar */
        }

        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            margin: 10px 0;
            padding: 10px;
            border-radius: 5px;
            transition: background-color 0.3s;
        }

        .sidebar a:hover {
            background-color: #a0fb0e;
            /* Color principal en hover */
            color: #000;
        }

        .main-content {
            margin-left: 250px;
            /* Deja espacio para el sidebar */
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            /* Ocupa al menos toda la altura de la pantalla */
        }

        .map-container {
    flex-grow: 1;
    /* Ocupa el espacio restante */
    height: calc(100vh - 230px);
    /* Ajusta la altura del mapa */
}


        .bottom-bar {
            padding: 20px;
            background-color: #fff;
            border-top: 1px solid #ddd;
            box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
        }

        .btn-success {
            background-color: #a0fb0e;
            /* Color principal para botones */
            border-color: #80c90b;
            color: #000;
        }

        .btn-success:hover {
            background-color: #80c90b;
            border-color: #609708;
        }

        .btn-danger {
            background-color: #dc3545;
            border-color: #dc3545;
        }

        .btn-danger:hover {
            background-color: #c82333;
            border-color: #bd2130;
        }

        /* Estilos para el menú desplegable */
        .profile-submenu {
            display: none;
            margin-left: 20px;
        }

        .profile-submenu.active {
            display: block;
        }

        .submenu-item {
            padding: 8px 10px;
            margin-top: 5px;
        }
        
    </style>
    @livewireStyles
</head>

<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar">
        <div class="mb-3 ml-8 flex flex-col items-center">
        @if (Auth::user()->rol != "Pasajero")
            <img src="{{ asset('storage/' . Auth::user()->foto) }}"
                alt="Foto de perfil"
            class="rounded-full w-14 h-14 object-cover">
        @endif
    <p class="font-bold mt-2">{{ Auth::user()->nombres }}</p>
</div>


            @if (Auth::user()->rol != 'Pasajero' && Auth::user()->rol != 'Administrador')
                <a href="{{ route('home-conductor') }}"><i class="mdi mdi-home"></i> Inicio</a>
                <a id="profile-link" class="cursor-pointer"><i class="mdi mdi-account"></i> Perfil</a>
                <div class="profile-submenu" id="profile-submenu">
                    <a href="{{ route('conductor.editar') }} " class="submenu-item"><i class="mdi mdi-account-edit"></i>
                        Editar perfila</a>

                    <a href="{{ route('vehiculo.index') }}" class="submenu-item"><i class="mdi mdi-car"></i> Editar vehículo</a>
                    <a href="{{ route('cambiar-contrasena') }}" class="submenu-item"><i class="mdi mdi-account-key"></i> Cambiar contraseña</a>

                </div>
            @elseif (Auth::user()->rol == 'Pasajero')
                <a href="{{ route('home') }}"><i class="mdi mdi-home"></i> Inicio</a>
                <a id="profile-link"><i class="mdi mdi-account"></i> Perfil</a>
                <div class="profile-submenu" id="profile-submenu">
                    <a href="{{ route('persona.editar') }} " class="submenu-item"><i class="mdi mdi-account-edit"></i>
                        Editar perfil</a>
                    <a href="{{ route('cambiar-contrasena') }}" class="submenu-item"><i class="mdi mdi-car"></i>Cambiar contraseña</a>
                </div>
            @endif
            @if (Auth::user()->rol == 'Administrador')
                <a id="profile-link"><i class="mdi mdi-account"></i> Perfil</a>
                <div class="profile-submenu" id="profile-submenu">
                    <a href="{{ route('admin.editar') }} " class="submenu-item"><i class="mdi mdi-account-edit"></i> Editar perfil</a>
                    <a href="{{ route('admin.registro') }}" class="submenu-item"><i class="mdi mdi-account-plus"></i> Registrarse</a>
                    <a href="{{ route('cambiar-contrasena') }}" class="submenu-item"><i class="mdi mdi-car"></i>Cambiar contraseña</a>

                </div>
                <a href="{{ route('admin.home') }}"><i class="mdi mdi-account-group"></i> Administración</a>
                <a href="{{ route('admin.conductores') }}"><i class="mdi mdi-account"></i> Conductores</a>
                <a href="{{ route('admin.pasajeros') }}"><i class="mdi mdi-account-multiple"></i> Pasajeros</a>
            @endif
            <a href="#"><i class="mdi mdi-history"></i> Historias de viajes</a>
            <a href="#"><i class="mdi mdi-alert-circle"></i> Incidencias</a>
            <form action="{{ route('logout') }}" method="POST">
            @csrf
                <button type="submit">
                    <i class="mdi mdi-logout"></i> Cerrar sesión
                </button>
            </form>

        </div>
        <!-- Main content -->
        <div class="main-content">
            @yield('content')
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Script para mostrar/ocultar el submenú de perfil
        document.addEventListener('DOMContentLoaded', function() {
            const profileLink = document.getElementById('profile-link');
            const profileSubmenu = document.getElementById('profile-submenu');

            profileLink.addEventListener('click', function(e) {
                e.preventDefault();
                profileSubmenu.classList.toggle('active');
            });
        });
    </script>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet-routing-machine@3.2.12/dist/leaflet-routing-machine.js"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @stack('scripts')
    @livewireScripts
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</body>

</html>
