<div>
    <label class="inline-flex items-center cursor-pointer">
        <input
            type="checkbox"
            class="sr-only peer"
            wire:model="disponible"
            wire:change="cambiarEstado"
        >
        <div class="relative w-11 h-6 rounded-full transition-all duration-300 
            {{ $disponible ? 'bg-blue-600' : 'bg-gray-200' }}
            peer-focus:outline-none peer-focus:ring-4 
            peer-focus:ring-blue-300 dark:peer-focus:ring-blue-800
            after:content-[''] after:absolute after:top-[2px] after:start-[2px]
            after:bg-white after:border-gray-300 after:border 
            after:rounded-full after:h-5 after:w-5 after:transition-all
            {{ $disponible ? 'after:translate-x-full' : '' }}"
        ></div>
        <span class="ms-3 text-sm font-medium 
            {{ $disponible ? 'text-blue-600' : 'text-gray-900' }} 
            dark:text-gray-300">
            {{ $disponible ? 'Activado' : 'Desactivado' }}
        </span>
    </label>
</div>