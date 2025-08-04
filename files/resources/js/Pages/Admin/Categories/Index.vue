<script setup>
import { ref } from "vue";
import { Link, useForm } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import SimplePagination from "@/Components/SimplePagination.vue";
import FlashMessage from "@/Components/FlashMessage.vue";
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";

const form = useForm({});

// Define props with validation
const props = defineProps({
  categories: {
    type: Object,
    required: true,
    default: () => ({
      data: [],
      meta: {
        current_page: 1,
        from: 1,
        last_page: 1,
        links: [],
        path: "",
        per_page: 10,
        to: 1,
        total: 0,
      },
    }),
  },
  flash: {
    type: Object,
    default: () => ({
      success: null,
      error: null,
    }),
  },
});

const showDeleteModal = ref(false);
const categoryToDelete = ref(null);
const processing = ref(false);

const confirmDelete = (category) => {
  categoryToDelete.value = category;
  showDeleteModal.value = true;
};

const getImageUrl = (imagePath) => {
  // Remove leading slash if it exists
  const cleanPath = imagePath.startsWith("/") ? imagePath.slice(1) : imagePath;
  return `${window.location.origin}/storage/${cleanPath}`;
};

const deleteCategory = () => {
  processing.value = true;
  form.delete(route("admin.categories.destroy", categoryToDelete.value.id), {
    preserveScroll: true,
    onSuccess: () => {
      showDeleteModal.value = false;
      processing.value = false;
    },
    onError: () => {
      processing.value = false;
    },
    onFinish: () => {
      processing.value = false;
    },
  });
};
</script>

<template>
  <AdminLayout title="Categories">
    <div class="flex justify-between items-center">
      <h2
        class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200"
      >Categories Management</h2>
      <Link
        :href="route('admin.categories.create')"
        class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md shadow-sm transition dark:bg-indigo-500 dark:hover:bg-indigo-600"
      >
        <i class="fas fa-plus mr-2"></i>
        Add Category
      </Link>
    </div>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6">
            <!-- Flash Messages -->
            <FlashMessage v-if="flash.success" :message="flash.success" class="mb-4" />

            <!-- Categories Table -->
            <div v-if="categories.data.length > 0" class="overflow-x-auto">
              <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead>
                  <tr class="bg-gray-50 dark:bg-gray-700/50">
                    <th
                      scope="col"
                      class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-20"
                    >Image</th>
                    <th
                      scope="col"
                      class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                    >Name</th>
                    <th
                      scope="col"
                      class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider"
                    >Foods Count</th>
                    <th
                      scope="col"
                      class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-32"
                    >Status</th>
                    <th
                      scope="col"
                      class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-40"
                    >Actions</th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                  <tr
                    v-for="category in categories.data"
                    :key="category.id"
                    class="group transition-colors duration-150 hover:bg-gray-50/90 dark:hover:bg-gray-700/50"
                  >
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="relative w-12 h-12">
                        <img
                          v-if="category.image_path"
                          :src="getImageUrl(category.image_path)"
                          class="w-12 h-12 rounded-lg object-cover shadow-sm border border-gray-200 dark:border-gray-600"
                          :alt="category.name"
                          @error="handleImageError"
                        />
                        <div
                          v-else
                          class="w-12 h-12 rounded-lg bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 flex items-center justify-center"
                        >
                          <i class="fas fa-camera text-gray-400 dark:text-gray-500 text-xl"></i>
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div
                        class="text-sm font-medium text-gray-900 dark:text-white group-hover:text-gray-900 dark:group-hover:text-white"
                      >{{ category.name }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200"
                      >
                        {{ category.foods_count }}
                        <span
                          class="ml-1 text-gray-500 dark:text-gray-400"
                        >items</span>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                      <span
                        :class="[
                        'inline-flex items-center px-3 py-1 rounded-full text-xs font-medium',
                        category.is_active
                          ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300 border border-green-200 dark:border-green-800'
                          : 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300 border border-red-200 dark:border-red-800'
                      ]"
                      >
                        <span class="relative flex h-2 w-2 mr-2">
                          <span
                            :class="[
                            'animate-ping absolute inline-flex h-full w-full rounded-full opacity-75',
                            category.is_active ? 'bg-green-400 dark:bg-green-500' : 'bg-red-400 dark:bg-red-500'
                          ]"
                          ></span>
                          <span
                            :class="[
                            'relative inline-flex rounded-full h-2 w-2',
                            category.is_active ? 'bg-green-500 dark:bg-green-400' : 'bg-red-500 dark:bg-red-400'
                          ]"
                          ></span>
                        </span>
                        {{ category.is_active ? 'Active' : 'Inactive' }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                      <div class="flex items-center space-x-4">
                        <Link
                          :href="route('admin.categories.edit', category.id)"
                          class="inline-flex items-center px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 active:bg-indigo-200 dark:bg-indigo-900/50 dark:hover:bg-indigo-800/50 text-indigo-600 dark:text-indigo-300 rounded-md transition-colors duration-200"
                        >
                          <i class="fas fa-edit text-xs mr-1.5"></i>
                          <span>Edit</span>
                        </Link>
                        <button
                          @click="confirmDelete(category)"
                          class="inline-flex items-center px-3 py-1.5 bg-red-50 hover:bg-red-100 active:bg-red-200 dark:bg-red-900/50 dark:hover:bg-red-800/50 text-red-600 dark:text-red-300 rounded-md transition-colors duration-200"
                        >
                          <i class="fas fa-trash text-xs mr-1.5"></i>
                          <span>Delete</span>
                        </button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Empty State -->
            <div v-else class="text-center py-12">
              <i class="fas fa-folder-open text-gray-400 text-5xl mb-4"></i>
              <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">No Categories Found</h3>
              <p
                class="mt-1 text-sm text-gray-500 dark:text-gray-400"
              >Get started by creating a new category.</p>
              <div class="mt-6">
                <Link
                  :href="route('admin.categories.create')"
                  class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md"
                >
                  <i class="fas fa-plus mr-2"></i>
                  Add Category
                </Link>
              </div>
            </div>

            <!-- Pagination -->
            <div v-if="categories.data.length > 0" class="mt-4">
              <SimplePagination :links="categories.meta.links" :meta="categories.meta" />
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <Modal :show="showDeleteModal" @close="showDeleteModal = false">
      <div class="p-6">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Delete Category</h2>
        <p
          class="mt-1 text-sm text-gray-600 dark:text-gray-400"
        >Are you sure you want to delete this category? This action cannot be undone.</p>
        <div class="mt-6 flex justify-end space-x-3">
          <SecondaryButton @click="showDeleteModal = false">Cancel</SecondaryButton>
          <DangerButton
            class="ml-3"
            :class="{ 'opacity-25': processing }"
            :disabled="processing"
            @click="deleteCategory"
          >Delete Category</DangerButton>
        </div>
      </div>
    </Modal>
  </AdminLayout>
</template>
