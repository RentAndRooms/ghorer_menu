<template>
  <AdminLayout>
    <div class="container mx-auto py-6">
      <!-- Header -->
      <div class="mb-6 sm:flex sm:items-center sm:justify-between">
        <h1 class="text-2xl font-semibold text-gray-900 dark:text-white">Orders</h1>
      </div>

      <!-- Filters -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
        <div class="p-4 space-y-4">
          <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search -->
            <div>
              <input v-model="search" type="text" placeholder="Search by ID, customer" @input="updateFilters"
                class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500" />
            </div>

            <!-- Status Filter -->
            <div>
              <select v-model="filters.status" @change="updateFilters"
                class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                <option value="">All Statuses</option>
                <option v-for="status in statuses" :key="status" :value="status">
                  {{ formatStatus(status) }}
                </option>
              </select>
            </div>

            <!-- Payment Status Filter -->
            <div>
              <select v-model="filters.payment_status" @change="updateFilters"
                class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                <option value="">All Payment Statuses</option>
                <option v-for="status in payment_statuses" :key="status" :value="status">
                  {{ formatStatus(status) }}
                </option>
              </select>
            </div>

            <!-- Order Type Filter -->
            <!-- <div>
              <select v-model="filters.order_type" @change="updateFilters"
                class="w-full px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                <option value="">All Orders</option>
                <option value="delivery">Delivery</option>
                <option value="collection">Collection</option>
              </select>
            </div> -->
          </div>
        </div>
      </div>

      <!-- Orders Table -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-700">
              <tr>
                <th scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  Order ID
                </th>
                <th scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  Customer
                </th>
                <th scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  Branch
                </th>
                <th scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  Total
                </th>
                <th scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  Status
                </th>
                <th scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  Payment
                </th>
                <th scope="col"
                  class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                  Date
                </th>
                <th scope="col" class="relative px-6 py-3">
                  <span class="sr-only">Actions</span>
                </th>
              </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
              <tr v-for="order in orders.data" :key="order.id" :class="{
                'bg-blue-50 dark:bg-blue-900/20': order.order_type === 'delivery',
                'bg-purple-50 dark:bg-purple-900/20': order.order_type === 'collection'
              }">
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="text-sm font-medium text-gray-900 dark:text-white">
                    #{{ order.id }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm text-gray-900 dark:text-white">
                    {{ order.user.name }}
                  </div>
                  <div class="text-sm text-gray-500 dark:text-gray-400">
                    {{ order.user.email }}
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="text-sm text-gray-900 dark:text-white">
                    {{ order.branch.name }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="text-sm font-medium text-gray-900 dark:text-white">
                    ${{ order.total_amount }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <select v-model="order.status" @change="updateOrderStatus(order)"
                    class="text-sm rounded-full px-3 py-1 font-medium" :class="getStatusClasses(order.status)">
                    <option v-for="status in statuses" :key="status" :value="status">
                      {{ formatStatus(status) }}
                    </option>
                  </select>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="text-sm px-2 py-1 rounded-full font-medium"
                    :class="getPaymentStatusClasses(order.payment_status)">
                    {{ formatStatus(order.payment_status) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                  {{ formatDate(order.created_at) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                  <Link :href="route('admin.orders.show', order.id)"
                    class="text-orange-600 hover:text-orange-900 dark:hover:text-orange-400">
                  View
                  </Link>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div class="px-4 py-3 border-t border-gray-200 dark:border-gray-700">
          <Pagination :links="orders.links" />
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from 'vue'
import { Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import Pagination from '@/Components/Pagination.vue'
import debounce from 'lodash/debounce'

const props = defineProps({
  orders: Object,
  filters: Object,
  statuses: Array,
  payment_statuses: Array
})

// Search and filters
const search = ref(props.filters.search || '')
const filters = ref({
  status: props.filters.status || '',
  payment_status: props.filters.payment_status || '',
  order_type: props.filters.order_type || ''
})

// Debounced function to update filters
const updateFilters = debounce(() => {
  router.get(
    route('admin.orders.index'),
    {
      search: search.value || null,
      status: filters.value.status || null,
      payment_status: filters.value.payment_status || null,
      order_type: filters.value.order_type || null
    },
    { preserveState: true, preserveScroll: true }
  )
}, 300)

// Status management
const updateOrderStatus = (order) => {
  router.patch(route('admin.orders.update-status', order.id), {
    status: order.status
  }, { preserveScroll: true })
}

// Utility functions
const formatStatus = (status) => {
  return status
    .split('_')
    .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
    .join(' ')
}

const formatDate = (date) => {
  return new Date(date).toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'short',
    day: 'numeric',
    hour: '2-digit',
    minute: '2-digit'
  })
}

// Status classes
const getStatusClasses = (status) => {
  const classes = {
    pending: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
    confirmed: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
    preparing: 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200',
    ready_for_delivery: 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200',
    out_for_delivery: 'bg-cyan-100 text-cyan-800 dark:bg-cyan-900 dark:text-cyan-200',
    delivered: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
    cancelled: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200'
  }
  return classes[status] || 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200'
}

const getPaymentStatusClasses = (status) => {
  const classes = {
    pending: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200',
    paid: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
    failed: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
    refunded: 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200'
  }
  return classes[status] || 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200'
}
</script>