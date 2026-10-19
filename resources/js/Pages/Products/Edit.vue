<script setup>
/**
 * Products/Edit.vue
 * Formulario de edición de producto, similar a Create pero con datos precargados.
 * Envía PUT a /products/{id}.
 */
import { useForm } from '@inertiajs/vue3';
import AdminLayout from '@/Layouts/AdminLayout.vue';

const props = defineProps({
  product: Object,    // Datos del producto precargados desde el backend
  categories: Array,  // [{ id, name, icon }]
});

// Formulario Inertia inicializado con los datos actuales del producto
const form = useForm({
  code: props.product.code ?? '',
  name: props.product.name ?? '',
  category_id: props.product.category_id ?? '',
  base_price: props.product.base_price ?? '',
  cost: props.product.cost ?? '',
  short_description: props.product.short_description ?? '',
  long_description: props.product.long_description ?? '',
  is_active: !!props.product.is_active,
  is_visible_web: !!props.product.is_visible_web,
  is_visible_pos: !!props.product.is_visible_pos,
  includes_igv: !!props.product.includes_igv,
});

/** Enviar actualización vía PUT; errores server-side en form.errors. */
function submit() {
  form.put(`/products/${props.product.id}`, { preserveScroll: true });
}
</script>

<template>
  <AdminLayout>
    <div class="mb-6">
      <h1 class="text-3xl font-bold text-gray-900">Editar Producto</h1>
      <p class="mt-2 text-sm text-gray-600">
        Modificando <span class="font-medium">{{ product.name }}</span> ({{ product.code }})
      </p>
    </div>

    <form @submit.prevent="submit" class="space-y-6">
      <!-- Datos generales -->
      <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Información general</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
          <!-- Código -->
          <div>
            <label for="code" class="block text-sm font-medium text-gray-700">
              Código <span class="text-red-500">*</span>
            </label>
            <input
              id="code"
              v-model="form.code"
              type="text"
              class="mt-1 block w-full rounded-md border px-3 py-2 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm"
              :class="form.errors.code ? 'border-red-500' : 'border-gray-300'"
            />
            <p v-if="form.errors.code" class="mt-1 text-sm text-red-600">{{ form.errors.code }}</p>
          </div>

          <!-- Nombre -->
          <div>
            <label for="name" class="block text-sm font-medium text-gray-700">
              Nombre <span class="text-red-500">*</span>
            </label>
            <input
              id="name"
              v-model="form.name"
              type="text"
              class="mt-1 block w-full rounded-md border px-3 py-2 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm"
              :class="form.errors.name ? 'border-red-500' : 'border-gray-300'"
            />
            <p v-if="form.errors.name" class="mt-1 text-sm text-red-600">{{ form.errors.name }}</p>
          </div>

          <!-- Categoría -->
          <div>
            <label for="category_id" class="block text-sm font-medium text-gray-700">Categoría</label>
            <select
              id="category_id"
              v-model="form.category_id"
              class="mt-1 block w-full rounded-md border bg-white px-3 py-2 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm"
              :class="form.errors.category_id ? 'border-red-500' : 'border-gray-300'"
            >
              <option value="">— Sin categoría —</option>
              <option v-for="cat in categories" :key="cat.id" :value="cat.id">
                {{ cat.icon }} {{ cat.name }}
              </option>
            </select>
            <p v-if="form.errors.category_id" class="mt-1 text-sm text-red-600">{{ form.errors.category_id }}</p>
          </div>

          <!-- Precios -->
          <div class="grid grid-cols-2 gap-4">
            <div>
              <label for="base_price" class="block text-sm font-medium text-gray-700">
                Precio venta (S/) <span class="text-red-500">*</span>
              </label>
              <input
                id="base_price"
                v-model="form.base_price"
                type="number"
                step="0.01"
                min="0"
                class="mt-1 block w-full rounded-md border px-3 py-2 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm"
                :class="form.errors.base_price ? 'border-red-500' : 'border-gray-300'"
              />
              <p v-if="form.errors.base_price" class="mt-1 text-sm text-red-600">{{ form.errors.base_price }}</p>
            </div>
            <div>
              <label for="cost" class="block text-sm font-medium text-gray-700">Costo (S/)</label>
              <input
                id="cost"
                v-model="form.cost"
                type="number"
                step="0.01"
                min="0"
                class="mt-1 block w-full rounded-md border px-3 py-2 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm"
                :class="form.errors.cost ? 'border-red-500' : 'border-gray-300'"
              />
              <p v-if="form.errors.cost" class="mt-1 text-sm text-red-600">{{ form.errors.cost }}</p>
            </div>
          </div>
        </div>

        <!-- Descripciones -->
        <div class="mt-6 space-y-4">
          <div>
            <label for="short_description" class="block text-sm font-medium text-gray-700">Descripción corta</label>
            <input
              id="short_description"
              v-model="form.short_description"
              type="text"
              class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm"
            />
          </div>
          <div>
            <label for="long_description" class="block text-sm font-medium text-gray-700">Descripción larga</label>
            <textarea
              id="long_description"
              v-model="form.long_description"
              rows="4"
              class="mt-1 block w-full rounded-md border border-gray-300 px-3 py-2 shadow-sm focus:border-brand-500 focus:ring-brand-500 sm:text-sm"
            />
          </div>
        </div>
      </div>

      <!-- Opciones / checkboxes -->
      <div class="bg-white shadow rounded-lg p-6">
        <h2 class="text-lg font-medium text-gray-900 mb-4">Opciones del producto</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
          <label class="flex items-center gap-3 rounded-md border border-gray-200 p-3 hover:bg-gray-50 cursor-pointer">
            <input
              type="checkbox"
              v-model="form.is_active"
              class="h-4 w-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500"
            />
            <span class="text-sm text-gray-700">Activo</span>
          </label>
          <label class="flex items-center gap-3 rounded-md border border-gray-200 p-3 hover:bg-gray-50 cursor-pointer">
            <input
              type="checkbox"
              v-model="form.is_visible_web"
              class="h-4 w-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500"
            />
            <span class="text-sm text-gray-700">Visible en Web</span>
          </label>
          <label class="flex items-center gap-3 rounded-md border border-gray-200 p-3 hover:bg-gray-50 cursor-pointer">
            <input
              type="checkbox"
              v-model="form.is_visible_pos"
              class="h-4 w-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500"
            />
            <span class="text-sm text-gray-700">Visible en POS</span>
          </label>
          <label class="flex items-center gap-3 rounded-md border border-gray-200 p-3 hover:bg-gray-50 cursor-pointer">
            <input
              type="checkbox"
              v-model="form.includes_igv"
              class="h-4 w-4 rounded border-gray-300 text-brand-500 focus:ring-brand-500"
            />
            <span class="text-sm text-gray-700">Incluye IGV (18%)</span>
          </label>
        </div>
      </div>

      <!-- Acciones -->
      <div class="flex items-center justify-end gap-3">
        <a
          href="/products"
          class="rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50"
        >
          Cancelar
        </a>
        <button
          type="submit"
          :disabled="form.processing"
          class="inline-flex items-center rounded-md bg-brand-500 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-brand-600 disabled:opacity-50"
        >
          {{ form.processing ? 'Guardando…' : 'Actualizar Producto' }}
        </button>
      </div>
    </form>
  </AdminLayout>
</template>
