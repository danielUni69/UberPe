<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrar Conductor</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .bg-primary {
            background-color: #a0fb0e;
        }

        .bg-secondary {
            background-color: #80c90b;
        }

        .bg-accent {
            background-color: #609708;
        }

        .text-primary {
            color: #000000;
        }

        .text-secondary {
            color: #406406;
        }

        .border-primary {
            border-color: #203203;
        }
    </style>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold text-primary mb-6">Registrar Conductor</h1>

        <!-- Formulario multi-paso -->
        <form method="POST" action="{{ route('conductor.registro') }}" class="bg-white p-6 rounded-lg shadow-lg">
            @csrf

            <!-- Indicador de pasos -->
            <div class="flex justify-center mb-8">
                <div class="step-indicator bg-secondary text-white w-8 h-8 rounded-full flex items-center justify-center mx-2"
                    data-step="1">1</div>
                <div class="step-indicator bg-gray-300 text-gray-600 w-8 h-8 rounded-full flex items-center justify-center mx-2"
                    data-step="2">2</div>
                <div class="step-indicator bg-gray-300 text-gray-600 w-8 h-8 rounded-full flex items-center justify-center mx-2"
                    data-step="3">3</div>
            </div>

            <!-- Paso 1 - Datos Personales -->
            <div class="form-step active" data-step="1">
                <h2 class="text-xl font-semibold text-secondary mb-4">Datos Personales</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="text" name="ci" placeholder="Cédula" class="p-2 border border-primary rounded">
                    <input type="text" name="nombres" placeholder="Nombres"
                        class="p-2 border border-primary rounded">
                    <input type="text" name="apellidos" placeholder="Apellidos"
                        class="p-2 border border-primary rounded">
                    <input type="text" name="telefono" placeholder="Teléfono"
                        class="p-2 border border-primary rounded">
                    <input type="email" name="email" placeholder="Email" class="p-2 border border-primary rounded">
                    <input type="text" name="usuario" placeholder="Usuario"
                        class="p-2 border border-primary rounded">
                    <input type="password" name="password" placeholder="Contraseña"
                        class="p-2 border border-primary rounded">
                    <input type="number" name="billetera" placeholder="Billetera"
                        class="p-2 border border-primary rounded">
                </div>
            </div>

            <!-- Paso 2 - Datos del Conductor -->
            <div class="form-step hidden" data-step="2">
                <h2 class="text-xl font-semibold text-secondary mb-4">Datos del Conductor</h2>
                <div class="grid grid-cols-1 gap-4">
                    <input type="text" name="licencia" placeholder="Licencia"
                        class="p-2 border border-primary rounded">
                    <select name="disponible" class="p-2 border border-primary rounded">
                        <option value="1">Disponible</option>
                        <option value="0">No disponible</option>
                    </select>
                </div>
            </div>

            <!-- Paso 3 - Datos del Vehículo -->
            <div class="form-step hidden" data-step="3">
                <h2 class="text-xl font-semibold text-secondary mb-4">Datos del Vehículo</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <input type="text" name="marca" placeholder="Marca" class="p-2 border border-primary rounded">
                    <input type="text" name="modelo" placeholder="Modelo" class="p-2 border border-primary rounded">
                    <input type="text" name="placa" placeholder="Placa" class="p-2 border border-primary rounded">
                    <input type="text" name="color" placeholder="Color" class="p-2 border border-primary rounded">
                </div>
            </div>

            <!-- Controles de navegación -->
            <div class="flex justify-between mt-6">
                <button type="button"
                    class="prev-step bg-accent text-white px-4 py-2 rounded hover:bg-secondary disabled:bg-gray-300"
                    disabled>Anterior</button>
                <button type="button"
                    class="next-step bg-accent text-white px-4 py-2 rounded hover:bg-secondary">Siguiente</button>
                <button type="submit"
                    class="final-step bg-primary text-black px-4 py-2 rounded hover:bg-secondary hidden">Guardar</button>
            </div>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const steps = document.querySelectorAll('.form-step');
            const stepIndicators = document.querySelectorAll('.step-indicator');
            const prevBtn = document.querySelector('.prev-step');
            const nextBtn = document.querySelector('.next-step');
            const finalBtn = document.querySelector('.final-step');
            let currentStep = 0;

            function updateSteps() {
                steps.forEach((step, index) => {
                    step.classList.toggle('hidden', index !== currentStep);
                });

                stepIndicators.forEach((indicator, index) => {
                    indicator.classList.toggle('bg-secondary', index === currentStep);
                    indicator.classList.toggle('bg-gray-300', index !== currentStep);
                });

                prevBtn.disabled = currentStep === 0;
                nextBtn.classList.toggle('hidden', currentStep === steps.length - 1);
                finalBtn.classList.toggle('hidden', currentStep !== steps.length - 1);
            }

            nextBtn.addEventListener('click', () => {
                if (currentStep < steps.length - 1) {
                    currentStep++;
                    updateSteps();
                }
            });

            prevBtn.addEventListener('click', () => {
                if (currentStep > 0) {
                    currentStep--;
                    updateSteps();
                }
            });
        });
    </script>
</body>

</html>
