<script setup>
/**
 * Products/Index.vue
 * Listado de productos con:
 * - Tabla: Código, Nombre, Categoría, Precio, Costo, Estado (toggle)
 * - Filtro por categoría (dropdown) y búsqueda por nombre
 * - Paginación de a 10 registros
 * - Botones Crear / Editar / Eliminar
 */
import { ref, watch } from 'vue';
import { Link, router, usePage } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
  products: Object,     // Paginador Laravel (data, links, meta)
  categories: Array,    // [{ id, name, icon }]
  filters: Object,      // { search, category_id, status }
});

const page = usePage();

// Flash messages (success / error) desde el backend
const flash = ref(null);
watch(
  () => page.props.flash,
  (value) => {
    flash.value = value;
    if (value?.success || value?.error) {
      setTimeout(() => (flash.value = null), 4000); // Autocierre a los 4s
    }
  },
  { immediate: true, deep: true }
);

// Estado local de los filtros (reactivo al formulario Inertia vía query string)
const search = ref(props.filters.search);
const categoryId = ref(props.filters.category_id);
const status = ref(props.filters.status);

let debounceTimer = null;

/** Aplicar filtros como query string con debounce para la búsqueda. */
function applyFilters(instantly = false) {
  clearTimeout(debounceTimer);
  const run = () => {
    router.get(
      '/products',
      {
        search: search.value || '',
        category_id: categoryId.value || '',
        status: status.value || '',
      },
      { preserveState: true, replace: true }
    );
  };
  instantly ? run() : (debounceTimer = setTimeout(run, 350));
}

/** Limpiar todos los filtros. */
function resetFilters() {
  search.value = '';
  categoryId.value = '';
  status.value = '';
  applyFilters(true);
}

/** Toggle de estado activo/inactivo vía PATCH. */
function toggleStatus(product) {
  router.patch(`/products/${product.id}/estado`, {}, { preserveScroll: true });
}

/** Eliminar producto (soft delete) con confirmación. */
function destroyProduct(product) {
  if (confirm(`¿Está seguro de eliminar el producto «${product.name}»?`)) {
    router.delete(`/products/${product.id}`, { preserveScroll: true });
  }
}

/** Formatear montos en soles. */
function money(value) {
  return `S/ ${Number(value ?? 0).toFixed(2)}`;
}
</script>

<template>
  <AdminLayout>
    <div class="mb-6 flex items-center justify-between">
      <div>
        <h1 class="text-3xl font-bold text-gray-900">Productos</h1>
        <p class="mt-2 text-sm text-gray-600">Gestión del menú — Restaurante Don Julio</p>
      </div>
      <!-- Botón crear nuevo -->
      <Link
        href="/products/crear"
        class="inline-flex items-center gap-2 rounded-md bg-brand-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-brand-600 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-brand-500"
      >
        <span class="text-lg leading-none">+</span> Nuevo Producto
      </Link>
    </div>

    <!-- Mensajes flash -->
    <div v-if="flash?.success" class="mb-4 rounded-md bg-green-50 border border-green-200 p-4 text-sm text-green-800">
      {{ flash.success }}
    </div>
    <div v-if="flash?.error" class="mb-4 rounded-md bg-red-50 border border-red-200 p-4 text-sm text-red-800">
      {{ flash.error }}
    </div>

    <!-- Card principal -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
      <!-- Barra de filtros -->
      <div class="border-b border-gray-200 px-6 py-4">
        <div class="flex flex-col sm:flex-row gap-3 sm:items-center">
          <!-- Búsqueda por nombre -->
          <div class="flex-1">
            <input
              v-model="search"
              @input="applyFilters()"
              type="text"
              placeholder="Buscar por nombre o código…"
              class="block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm px-3 py-2 border"
            />
          </div>
          <!-- Filtro por categoría -->
          <div>
            <select
              v-model="categoryId"
              @change="applyFilters(true)"
              class="rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm px-3 py-2 border"
            >
              <option value="">Todas las categorías</option>
              <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                {{ cat.icon }} {{ cat.name }}
              </option>
            </select>
          </div>
          <!-- Filtro por estado -->
          <div>
            <select
              v-model="status"
              @change="applyFilters(true)"
              class="rounded-md border-gray-300 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm px-3 py-2 border"
            >
              <option value="">Todos los estados</option>
              <option value="active">Activos</option>
              <option value="inactive">Inactivos</option>
            </select>
          </div>
          <!-- Limpiar filtros -->
          <button
            @click="resetFilters"
            class="rounded-md border border-gray-300 bg-white px-3 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50"
          >
            Limpiar
          </button>
        </div>
      </div>

      <!-- Tabla de productos -->
      <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
          <thead class="bg-gray-50">
            <tr>
              <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Código</th>
              <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Nombre</th>
              <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Categoría</th>
              <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Precio</th>
              <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Costo</th>
              <th class="px-6 py-3 text-center text-xs font-medium uppercase tracking-wider text-gray-500">Estado</th>
              <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider text-gray-500">Acciones</th>
            </tr>
          </thead>
          <tbody class="divide-y divide-gray-200 bg-white">
            <tr v-if="products.data.length === 0">
              <td colspan="7" class="px-6 py-10 text-center text-sm text-gray-500">
                No se encontraron productos con los criterios actuales.
              </td>
            </tr>
            <tr v-for="product in products.data" :key="product.id" class="hover:bg-gray-50">
              <td class="whitespace-nowrap px-6 py-4 text-sm font-mono text-gray-500">{{ product.code }}</td>
              <td class="whitespace-nowrap px-6 py-4 text-sm font-medium text-gray-900">
                {{ product.name }}
                <div v-if="product.short_description" class="text-xs font-normal text-gray-500 truncate max-w-xs">
                  {{ product.short_description }}
                </div>
              </td>
              <td class="whitespace-nowrap px-6 py-4 text-sm text-gray-700">
                <span
                  v-if="product.category"
                  class="inline-flex items-center rounded-full bg-brand-50 px-2.5 py-0.5 text-xs font-medium text-brand-700"
                >
                  {{ product.category.icon }} {{ product.category.name }}
                </span>
                <span v-else class="text-gray-400">Sin categoría</span>
              </td>
              <td class="whitespace-nowrap px-6 py-4 text-sm text-right text-gray-900">{{ money(product.base_price) }}</td>
              <td class="whitespace-nowrap px-6 py-4 text-sm text-right text-gray-500">{{ money(product.cost) }}</td>
              <td class="whitespace-nowrap px-6 py-4 text-center">
                <!-- Toggle de estado activo/inactivo -->
                <button
                  @click="toggleStatus(product)"
                  :title="product.is_active ? 'Clic para desactivar' : 'Clic para activar'"
                  class="relative inline-flex h-6 w-11 shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none"
                  :class="product.is_active ? 'bg-brand-500' : 'bg-gray-300'"
                >
                  <span
                    class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                    :class="product.is_active ? 'translate-x-5' : 'translate-x-0'"
                  />
                </button>
                <span class="block mt-1 text-xs" :class="product.is_active ? 'text-green-600' : 'text-gray-400'">
                  {{ product.is_active ? 'Activo' : 'Inactivo' }}
                </span>
              </td>
              <td class="whitespace-nowrap px-6 py-4 text-right text-sm font-medium">
                <Link
                  :href="`/products/${product.id}/editar`"
                  class="text-brand-600 hover:text-brand-800 mr-3"
                >
                  Editar
                </Link>
                <button
                  @click="destroyProduct(product)"
                  class="text-red-600 hover:text-red-800"
                >
                  Eliminar
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Paginación -->
      <div class="border-t border-gray-200 px-6 py-4 flex items-center justify-between">
        <p class="text-sm text-gray-600">
          Mostrando
          <span class="font-medium">{{ products.from ?? 0 }}</span>
          a
          <span class="font-medium">{{ products.to ?? 0 }}</span>
          de
          <span class="font-medium">{{ products.total }}</span>
          productos
        </p>
        <nav class="flex items-center gap-1">
          <template v-for="link in products.links" :key="link.label">
            <Link
              v-if="link.url"
              :href="link.url"
              preserve-scroll
              class="relative inline-flex items-center rounded-md border px-3 py-1.5 text-sm"
              :class="link.active
                ? 'border-brand-500 bg-brand-500 text-white'
                : 'border-gray-300 bg-white text-gray-700 hover:bg-gray-50'"
              v-html="link.label"
            />
            <span
              v-else
              class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-3 py-1.5 text-sm text-gray-400"
              v-html="link.label"
            />
          </template>
        </nav>
      </div>
    </div>
  </AdminLayout>
</template>
