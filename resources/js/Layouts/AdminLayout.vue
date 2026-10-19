<script setup>
import { computed } from 'vue';
import { Link, usePage } from '@inertiajs/vue3';

// Enlace activo según la ruta actual (resaltado dinámico del menú)
const page = usePage();
const currentPath = computed(() => page.url);

function isActive(prefix) {
  return currentPath.value.startsWith(prefix);
}

const linkActive = 'border-brand-500 text-gray-900 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium';
const linkInactive = 'border-transparent text-gray-500 hover:border-gray-300 hover:text-gray-700 inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium';
</script>

<template>
  <div class="min-h-screen bg-gray-100">
    <!-- Header -->
    <nav class="bg-white border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <div class="flex">
            <div class="flex-shrink-0 flex items-center">
              <Link href="/dashboard" class="text-2xl font-bold text-brand-600">
                ️ Don Julio
              </Link>
            </div>
            <div class="hidden sm:ml-6 sm:flex sm:space-x-8">
              <Link href="/dashboard" :class="isActive('/dashboard') ? linkActive : linkInactive">
                Dashboard
              </Link>
              <Link href="/products" :class="isActive('/products') ? linkActive : linkInactive">
                Productos
              </Link>
              <Link href="/salon" :class="isActive('/salon') ? linkActive : linkInactive">
                Salón
              </Link>
              <Link href="/kitchen" :class="isActive('/kitchen') ? linkActive : linkInactive">
                Cocina
              </Link>
            </div>
          </div>
          <div class="flex items-center">
            <span class="text-sm text-gray-700">Admin</span>
          </div>
        </div>
      </div>
    </nav>

    <!-- Contenido -->
    <main class="py-10">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <slot />
      </div>
    </main>
  </div>
</template>
