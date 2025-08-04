<script setup>
import { Link } from '@inertiajs/vue3';
import OrderStatus from '@/Components/OrderStatus.vue';
import Pagination from '@/Components/Pagination.vue';
import CustomerLayout from '@/Layouts/CustomerLayout.vue';
import { ref, computed } from 'vue';

const props = defineProps({
    orders: Object,
});

const searchQuery = ref('');
const statusFilter = ref('all');

// Available statuses matching your enum
const orderStatuses = [
    { value: 'all', label: 'All Orders' },
    { value: 'pending', label: 'Pending' },
    { value: 'confirmed', label: 'Confirmed' },
    { value: 'preparing', label: 'Preparing' },
    { value: 'ready_for_delivery', label: 'Ready for Delivery' },
    { value: 'out_for_delivery', label: 'Out for Delivery' },
    { value: 'delivered', label: 'Delivered' },
    { value: 'cancelled', label: 'Cancelled' }
];

const formatDate = (date) => {
    return new Date(date).toLocaleDateString('en-US', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    });
};

const filteredOrders = computed(() => {
    return props.orders.data.filter(order => {
        const matchesSearch = searchQuery.value === '' ||
            order.id.toString().includes(searchQuery.value) ||
            order.branch.name.toLowerCase().includes(searchQuery.value.toLowerCase());

        const matchesStatus = statusFilter.value === 'all' ||
            order.status === statusFilter.value;

        return matchesSearch && matchesStatus;
    });
});

// Payment status classes
const getPaymentStatusClasses = (status) => {
    const classes = {
        pending: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300',
        paid: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300',
        failed: 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300',
        refunded: 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300'
    }
    return classes[status] || 'bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-300'
}
</script>


<template>
    <CustomerLayout>
        <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
            <!-- Header Section -->
            <div class="md:flex md:items-center md:justify-between mb-6">
                <div class="flex-1 min-w-0">
                    <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                        My Orders
                    </h1>
                    <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                        View and track all your orders
                    </p>
                </div>
            </div>

            <!-- Filters Section -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-4 mb-6">
                <div class="sm:flex sm:justify-between sm:items-center">
                    <!-- Search -->
                    <div class="flex-1 min-w-0 max-w-lg">
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" v-model="searchQuery"
                                class="block w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100"
                                placeholder="Search orders..." />
                        </div>
                    </div>

                    <!-- Status Filter -->
                    <div class="mt-3 sm:mt-0 sm:ml-4">
                        <select v-model="statusFilter"
                            class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                            <option v-for="status in orderStatuses" :key="status.value" :value="status.value">
                                {{ status.label }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Orders List -->
            <div class="bg-white dark:bg-gray-800 shadow overflow-hidden sm:rounded-lg">
                <div v-if="filteredOrders.length === 0" class="text-center py-12">
                    <i class="fas fa-receipt text-4xl text-gray-400 mb-4"></i>
                    <p class="text-gray-500 dark:text-gray-400">No orders found</p>
                </div>

                <ul v-else class="divide-y divide-gray-200 dark:divide-gray-700">
                    <li v-for="order in filteredOrders" :key="order.id">
                        <Link :href="route('customer.orders.show', order.id)"
                            class="block hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-150">
                        <div class="px-4 py-4 sm:px-6">
                            <div class="flex items-center justify-between">
                                <div class="flex flex-col sm:flex-row sm:items-center">
                                    <div class="flex items-center">
                                        <span
                                            class="flex-shrink-0 text-lg font-medium text-indigo-600 dark:text-indigo-400">
                                            #{{ order.id }}
                                        </span>
                                        <OrderStatus :status="order.status" class="ml-4" />
                                    </div>
                                    <span class="mt-2 sm:mt-0 sm:ml-6 text-sm text-gray-500 dark:text-gray-400">
                                        {{ formatDate(order.created_at) }}
                                    </span>
                                </div>
                                <div class="ml-4 flex items-center">
                                    <span class="text-lg font-medium text-gray-900 dark:text-gray-100">
                                        ${{ Number(order.total_amount).toFixed(2) }}
                                    </span>
                                    <i class="fas fa-chevron-right ml-4 text-gray-400"></i>
                                </div>
                            </div>

                            <div class="mt-4">
                                <div class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                                    <i class="fas fa-store mr-2"></i>
                                    {{ order.branch.name }}
                                </div>
                                <div class="mt-2 flex items-center text-sm">
                                    <span
                                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                                        :class="getPaymentStatusClasses(order.payment_status)">
                                        {{ order.payment_status.charAt(0).toUpperCase() + order.payment_status.slice(1)
                                        }}
                                    </span>
                                    <span class="mx-2">•</span>
                                    <span>
                                        {{ order.items.length }} {{ order.items.length === 1 ? 'item' : 'items' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        </Link>
                    </li>
                </ul>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                <Pagination :links="orders.links" />
            </div>
        </div>
    </CustomerLayout>
</template>

<style scoped>
.pb-safe {
    padding-bottom: env(safe-area-inset-bottom);
}
</style>
