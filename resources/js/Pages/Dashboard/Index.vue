<script setup>
import AdminLayout from '@/Layouts/AdminLayout.vue';
</script>

<template>
  <AdminLayout>
    <div class="mb-6">
      <h1 class="text-3xl font-bold text-gray-900">Dashboard Gerencial</h1>
      <p class="mt-2 text-sm text-gray-600">Bienvenido al sistema Don Julio</p>
    </div>

    <!-- KPIs -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
      <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
          <div class="text-sm font-medium text-gray-500 truncate">Ventas Hoy</div>
          <div class="mt-1 text-3xl font-semibold text-gray-900">S/ 0.00</div>
        </div>
      </div>
      <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
          <div class="text-sm font-medium text-gray-500 truncate">Pedidos Activos</div>
          <div class="mt-1 text-3xl font-semibold text-gray-900">0</div>
        </div>
      </div>
      <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
          <div class="text-sm font-medium text-gray-500 truncate">Mesas Ocupadas</div>
          <div class="mt-1 text-3xl font-semibold text-gray-900">0</div>
        </div>
      </div>
      <div class="bg-white overflow-hidden shadow rounded-lg">
        <div class="p-5">
          <div class="text-sm font-medium text-gray-500 truncate">Comandas en Cocina</div>
          <div class="mt-1 text-3xl font-semibold text-gray-900">0</div>
        </div>
      </div>
    </div>

    <!-- Contenido -->
    <div class="bg-white shadow rounded-lg p-6">
      <h2 class="text-lg font-medium text-gray-900 mb-4">Sistema Operativo</h2>
      <p class="text-gray-600">El sistema Don Julio está funcionando correctamente.</p>
      <div class="mt-4 grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="border border-gray-200 rounded-lg p-4">
          <div class="text-2xl">🍽️</div>
          <div class="mt-2 text-sm font-medium text-gray-900">Salón</div>
          <div class="text-xs text-gray-500">10 mesas</div>
        </div>
        <div class="border border-gray-200 rounded-lg p-4">
          <div class="text-2xl">‍🍳</div>
          <div class="mt-2 text-sm font-medium text-gray-900">Cocina</div>
          <div class="text-xs text-gray-500">3 estaciones</div>
        </div>
        <div class="border border-gray-200 rounded-lg p-4">
          <div class="text-2xl">💰</div>
          <div class="mt-2 text-sm font-medium text-gray-900">Caja</div>
          <div class="text-xs text-gray-500">2 cajas</div>
        </div>
        <div class="border border-gray-200 rounded-lg p-4">
          <div class="text-2xl">📦</div>
          <div class="mt-2 text-sm font-medium text-gray-900">Productos</div>
          <div class="text-xs text-gray-500">5 productos</div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>
