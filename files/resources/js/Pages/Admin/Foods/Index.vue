<template>
  <AdminLayout title="Foods">
    <div class="flex justify-between items-center">
      <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
        Food Items Management
      </h2>
      <Link :href="route('admin.foods.create')"
        class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md shadow-sm transition dark:bg-indigo-500 dark:hover:bg-indigo-600">
      <i class="fas fa-plus mr-2"></i>
      Add Food Item
      </Link>
    </div>
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
          <div class="p-6">
            <!-- Flash Messages -->
            <FlashMessage v-if="flash.success" :message="flash.success" class="mb-4" />

            <!-- Foods Table -->
            <div v-if="foods.data.length > 0" class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700/50">
                  <tr>
                    <th scope="col"
                      class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-40">
                      Food Item
                    </th>
                    <th scope="col"
                      class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                      Details
                    </th>
                    <th scope="col"
                      class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-32">
                      Price
                    </th>
                    <th scope="col"
                      class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-32">
                      Status
                    </th>
                    <th scope="col"
                      class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-40">
                      Actions
                    </th>
                  </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                  <tr v-for="food in foods.data" :key="food.id"
                    class="group transition-colors duration-150 hover:bg-gray-50/90 dark:hover:bg-gray-700/50">
                    <!-- Food Item Column -->
                    <td class="px-6 py-4">
                      <div class="flex items-center">
                        <div class="h-16 w-16 flex-shrink-0">
                          <img v-if="food.image_path" :src="getImageUrl(food.image_path)"
                            class="h-16 w-16 rounded-lg object-cover shadow-sm border border-gray-200 dark:border-gray-600"
                            :alt="food.name" />
                          <div v-else
                            class="h-16 w-16 rounded-lg bg-gray-100 dark:bg-gray-700 border border-gray-200 dark:border-gray-600 flex items-center justify-center">
                            <i class="fas fa-utensils text-gray-400 dark:text-gray-500 text-xl"></i>
                          </div>
                        </div>
                        <div class="ml-4">
                          <div class="font-medium text-gray-900 dark:text-white">
                            {{ food.name }}
                          </div>
                          <div class="text-sm text-gray-500 dark:text-gray-400">
                            {{ food.category.name }}
                          </div>
                        </div>
                      </div>
                    </td>

                    <!-- Details Column -->
                    <td class="px-6 py-4">
                      <div class="text-sm text-gray-900 dark:text-gray-100">
                        {{ food.branch.name }}
                      </div>
                      <div class="text-sm text-gray-500 dark:text-gray-400">
                        {{ food.extra_options_count }} Extra Options
                      </div>
                      <div class="flex items-center space-x-2 mt-1">
                        <span v-if="food.is_vegetarian"
                          class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300">
                          <i class="fas fa-leaf mr-1"></i>
                          Veg
                        </span>
                        <span v-if="food.is_spicy"
                          class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300">
                          <i class="fas fa-pepper-hot mr-1"></i>
                          Spicy
                        </span>
                      </div>
                    </td>

                    <!-- Price Column -->
                    <td class="px-6 py-4">
                      <div class="text-sm font-medium text-gray-900 dark:text-gray-100">
                        ${{ food.base_price }}
                      </div>
                      <div class="text-xs text-gray-500 dark:text-gray-400">
                        {{ food.preparation_time }}min prep
                      </div>
                    </td>

                    <!-- Status Column -->
                    <td class="px-6 py-4">
                      <button @click="updateStatus(food)" :class="[
                        'inline-flex items-center px-3 py-1 rounded-full text-xs font-medium',
                        food.is_available
                          ? 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300 border border-green-200 dark:border-green-800'
                          : 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300 border border-red-200 dark:border-red-800'
                      ]">
                        <span class="relative flex h-2 w-2 mr-2">
                          <span :class="[
                            'animate-ping absolute inline-flex h-full w-full rounded-full opacity-75',
                            food.is_available ? 'bg-green-400 dark:bg-green-500' : 'bg-red-400 dark:bg-red-500'
                          ]"></span>
                          <span :class="[
                            'relative inline-flex rounded-full h-2 w-2',
                            food.is_available ? 'bg-green-500 dark:bg-green-400' : 'bg-red-500 dark:bg-red-400'
                          ]"></span>
                        </span>
                        {{ food.is_available ? 'Available' : 'Unavailable' }}
                      </button>
                    </td>

                    <!-- Actions Column -->
                    <td class="px-6 py-4">
                      <div class="flex items-center space-x-4">
                        <Link :href="route('admin.foods.edit', food.id)"
                          class="inline-flex items-center px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 active:bg-indigo-200 dark:bg-indigo-900/50 dark:hover:bg-indigo-800/50 text-indigo-600 dark:text-indigo-300 rounded-md transition-colors duration-200">
                        <i class="fas fa-edit text-xs mr-1.5"></i>
                        <span>Edit</span>
                        </Link>
                        <button @click="confirmDelete(food)"
                          class="inline-flex items-center px-3 py-1.5 bg-red-50 hover:bg-red-100 active:bg-red-200 dark:bg-red-900/50 dark:hover:bg-red-800/50 text-red-600 dark:text-red-300 rounded-md transition-colors duration-200">
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
              <i class="fas fa-utensils text-gray-400 text-5xl mb-4"></i>
              <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">No Food Items Found</h3>
              <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Get started by creating a new food item.
              </p>
              <div class="mt-6">
                <Link :href="route('admin.foods.create')"
                  class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md">
                <i class="fas fa-plus mr-2"></i>
                Add Food Item
                </Link>
              </div>
            </div>

            <!-- Pagination -->
            <div v-if="foods.data.length > 0" class="mt-4">
              <SimplePagination :links="foods.meta.links" :meta="foods.meta" />
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <Modal :show="showDeleteModal" @close="showDeleteModal = false">
      <div class="p-6">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
          Delete Food Item
        </h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
          Are you sure you want to delete this food item? This action cannot be undone and will also remove all
          associated
          extra options.
        </p>
        <div class="mt-6 flex justify-end space-x-3">
          <SecondaryButton @click="showDeleteModal = false">
            <i class="fas fa-times mr-2"></i>
            Cancel
          </SecondaryButton>
          <DangerButton class="ml-3" :class="{ 'opacity-25': processing }" :disabled="processing" @click="deleteFood">
            <i class="fas fa-trash mr-2"></i>
            Delete Food Item
          </DangerButton>
        </div>
      </div>
    </Modal>

    <!-- Status Change Modal -->
    <Modal :show="showStatusModal" @close="showStatusModal = false">
      <div class="p-6">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
          Change Food Availability
        </h2>
        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
          Are you sure you want to {{ selectedFood?.is_available ? 'disable' : 'enable' }} this food item?
          {{ selectedFood?.is_available
            ? 'It will no longer be available for ordering.'
            : 'It will become available for ordering.' }}
        </p>
        <div class="mt-6 flex justify-end space-x-3">
          <SecondaryButton @click="showStatusModal = false">
            Cancel
          </SecondaryButton>
          <PrimaryButton :class="{ 'opacity-25': processing }" :disabled="processing" @click="updateFoodStatus">
            {{ selectedFood?.is_available ? 'Disable' : 'Enable' }} Food Item
          </PrimaryButton>
        </div>
      </div>
    </Modal>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import SimplePagination from '@/Components/SimplePagination.vue'
import FlashMessage from '@/Components/FlashMessage.vue'
import Modal from '@/Components/Modal.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import DangerButton from '@/Components/DangerButton.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'

const props = defineProps({
  foods: {
    type: Object,
    required: true,
    default: () => ({
      data: [],
      meta: {
        current_page: 1,
        from: 1,
        last_page: 1,
        links: [],
        path: '',
        per_page: 10,
        to: 1,
        total: 0
      }
    })
  },
  flash: {
    type: Object,
    default: () => ({
      success: null,
      error: null
    })
  }
})

// State management
const showDeleteModal = ref(false)
const showStatusModal = ref(false)
const selectedFood = ref(null)
const processing = ref(false)

// Image handling
const getImageUrl = (imagePath) => {
  if (!imagePath) return null
  const cleanPath = imagePath.startsWith('/') ? imagePath.slice(1) : imagePath
  return `${window.location.origin}/storage/${cleanPath}`
}

const handleImageError = (event) => {
  event.target.src = '/images/placeholder-food.png'
}

// Delete food item
const confirmDelete = (food) => {
  selectedFood.value = food
  showDeleteModal.value = true
}

const deleteFood = () => {
  if (!selectedFood.value) return

  processing.value = true
  useForm().delete(route('admin.foods.destroy', selectedFood.value.id), {
    preserveScroll: true,
    onSuccess: () => {
      showDeleteModal.value = false
      selectedFood.value = null
      processing.value = false
    },
    onError: () => {
      processing.value = false
    },
    onFinish: () => {
      processing.value = false
    }
  })
}

// Update food status
const confirmStatusChange = (food) => {
  selectedFood.value = food
  showStatusModal.value = true
}

const updateFoodStatus = () => {
  if (!selectedFood.value) return

  processing.value = true
  useForm().post(route('admin.foods.update-status', selectedFood.value.id), {
    preserveScroll: true,
    onSuccess: () => {
      showStatusModal.value = false
      selectedFood.value = null
      processing.value = false
    },
    onError: () => {
      processing.value = false
    },
    onFinish: () => {
      processing.value = false
    }
  })
}

// Format currency
const formatPrice = (price) => {
  return new Intl.NumberFormat('en-US', {
    style: 'currency',
    currency: 'USD'
  }).format(price)
}

// Format food information
const getFoodBadges = (food) => {
  const badges = []
  if (food.is_vegetarian) {
    badges.push({
      text: 'Vegetarian',
      icon: 'leaf',
      className: 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300'
    })
  }
  if (food.is_spicy) {
    badges.push({
      text: 'Spicy',
      icon: 'pepper-hot',
      className: 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300'
    })
  }
  return badges
}
</script>

<style scoped>
.status-badge {
  @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium;
}

.status-badge-dot {
  @apply -ml-0.5 mr-1.5 h-2 w-2 rounded-full;
}

.extra-options-count {
  @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200;
}

/* Hover effects */
.food-row {
  @apply transition-colors duration-150;
}

.food-row:hover .action-button {
  @apply opacity-100;
}

.action-button {
  @apply opacity-75 transition-opacity duration-150;
}

/* Image hover effect */
.food-image {
  @apply transition-transform duration-200;
}

.food-image:hover {
  @apply transform scale-105;
}

/* Badge animations */
@keyframes ping {

  75%,
  100% {
    transform: scale(2);
    opacity: 0;
  }
}

.status-dot-ping {
  animation: ping 1.5s cubic-bezier(0, 0, 0.2, 1) infinite;
}
</style>
```
