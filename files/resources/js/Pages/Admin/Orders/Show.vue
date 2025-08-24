<template>
  <AdminLayout>
    <div class="container mx-auto py-6">
      <!-- Back Button -->
      <Link :href="route('admin.orders.index')"
        class="inline-flex items-center text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200 mb-6">
      <i class="fas fa-arrow-left mr-2"></i>
      Back to Orders
      </Link>

      <!-- Order Header -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow mb-6">
        <div class="p-6">
          <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <div>
              <h1 class="text-2xl font-semibold text-gray-900 dark:text-white mb-2">Orders #{{ order.id }}</h1>
              <p class="text-sm text-gray-500 dark:text-gray-400">Placed {{ formatDate(order.created_at) }}</p>
            </div>
            <div class="mt-4 md:mt-0 space-y-2 md:space-y-0 md:space-x-2">
              <!-- Status Select -->
              <select v-model="order.status" @change="updateOrderStatus"
                class="px-4 py-2 rounded-lg border text-sm font-medium" :class="getStatusClasses(order.status)">
                <option v-for="status in orderStatuses" :key="status" :value="status">{{ formatStatus(status) }}
                </option>
              </select>

              <!-- Payment Status Select -->
              <select v-model="order.payment_status" @change="updatePaymentStatus"
                class="px-4 py-2 rounded-lg border text-sm font-medium"
                :class="getPaymentStatusClasses(order.payment_status)">
                <option v-for="status in paymentStatuses" :key="status" :value="status">{{ formatStatus(status) }}
                </option>
              </select>
            </div>
          </div>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Customer & Delivery Info -->
        <div class="md:col-span-1 space-y-6">
          <!-- Customer Information -->
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Customer Information</h2>
            <div class="space-y-3">
              <div>
                <label class="text-sm text-gray-500 dark:text-gray-400">Name</label>
                <p class="text-gray-900 dark:text-white">{{ order.user.name }}</p>
              </div>
              <div>
                <label class="text-sm text-gray-500 dark:text-gray-400">Email</label>
                <p class="text-gray-900 dark:text-white">{{ order.user.email }}</p>
              </div>
              <div>
                <label class="text-sm text-gray-500 dark:text-gray-400">Phone</label>
                <p class="text-gray-900 dark:text-white">{{ order.user.phone }}</p>
              </div>
            </div>
          </div>

          <!-- Delivery Information -->
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Delivery Information</h2>
            <div class="space-y-3">
              <div>
                <label class="text-sm text-gray-500 dark:text-gray-400">Branch</label>
                <p class="text-gray-900 dark:text-white">{{ order.branch.name }}</p>
              </div>
              <div>
                <label class="text-sm text-gray-500 dark:text-gray-400">Delivery Address</label>
                <p class="text-gray-900 dark:text-white">{{ order.delivery_address }}</p>
              </div>
              <div v-if="order.delivery_notes">
                <label class="text-sm text-gray-500 dark:text-gray-400">Delivery Notes</label>
                <p class="text-gray-900 dark:text-white">{{ order.delivery_notes }}</p>
              </div>
              <div>
                <label class="text-sm text-gray-500 dark:text-gray-400">Estimated Delivery</label>
                <p class="text-gray-900 dark:text-white">{{ formatDate(order.estimated_delivery_time) }}</p>
              </div>
            </div>
          </div>

          <!-- Payment Information -->
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Payment Information</h2>
            <div class="space-y-3">
              <div>
                <label class="text-sm text-gray-500 dark:text-gray-400">Payment Method</label>
                <p class="text-gray-900 dark:text-white capitalize">{{ order.payment_method }}</p>
              </div>
              <div>
                <label class="text-sm text-gray-500 dark:text-gray-400">Payment Status</label>
                <span class="inline-flex px-2.5 py-0.5 rounded-full text-sm font-medium mt-1"
                  :class="getPaymentStatusClasses(order.payment_status)">{{ formatStatus(order.payment_status) }}</span>
              </div>
            </div>
          </div>
        </div>

        <!-- Order Items & Summary -->
        <div class="md:col-span-2 space-y-6">
          <!-- Order Items -->
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow">
            <div class="p-6">
              <h2 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Order Items</h2>
              <div class="divide-y divide-gray-200 dark:divide-gray-700">
                <div v-for="item in order.items" :key="item.id" class="py-4">
                  <div class="flex items-center">
                    <!-- Food Image -->
                    <div class="h-16 w-16 flex-shrink-0 overflow-hidden rounded-lg bg-gray-200 dark:bg-gray-700">
                      <img v-if="item.food.image" :src="item.food.image" :alt="item.food.name"
                        class="h-full w-full object-cover" />
                      <div v-else class="h-full w-full flex items-center justify-center">
                        <i class="fas fa-utensils text-gray-400"></i>
                      </div>
                    </div>

                    <!-- Item Details -->
                    <div class="ml-4 flex-1">
                      <div class="flex justify-between">
                        <div>
                          <h4 class="text-gray-900 dark:text-white">{{ item.food.name }}</h4>
                          <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Qty: {{ item.quantity }} × ${{
                            item.unit_price }}</p>
                          <p>{{ item.order_type }}</p>
                        </div>
                        <p class="text-gray-900 dark:text-white font-medium">${{ item.subtotal }}</p>
                      </div>

                      <!-- Extras -->
                      <div v-if="item.extras?.length" class="mt-2">
                        <p class="text-sm text-gray-500 dark:text-gray-400">Extras:</p>
                        <ul class="mt-1 space-y-1">
                          <li v-for="extra in item.extras" :key="extra.id"
                            class="text-sm text-gray-500 dark:text-gray-400">- {{ extra.name }} (+${{ extra.price }})
                          </li>
                        </ul>
                      </div>

                      <!-- Special Instructions -->
                      <p v-if="item.special_instructions" class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                        <span class="font-medium">Note:</span>
                        {{ item.special_instructions }}
                      </p>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Order Summary -->
            <div class="border-t border-gray-200 dark:border-gray-700 p-6">
              <dl class="space-y-4">
                <div class="flex justify-between">
                  <dt class="text-gray-500 dark:text-gray-400">Subtotal</dt>
                  <dd class="text-gray-900 dark:text-white">${{ order.subtotal }}</dd>
                </div>
                <div class="flex justify-between">
                  <dt class="text-gray-500 dark:text-gray-400">Delivery Fee</dt>
                  <dd class="text-gray-900 dark:text-white">${{ order.delivery_fee }}</dd>
                </div>
                <div class="flex justify-between">
                  <dt class="text-gray-500 dark:text-gray-400">Tax</dt>
                  <dd class="text-gray-900 dark:text-white">${{ order.tax }}</dd>
                </div>
                <div class="flex justify-between pt-4 border-t border-gray-200 dark:border-gray-700">
                  <dt class="text-lg font-medium text-gray-900 dark:text-white">Total</dt>
                  <dd class="text-lg font-medium text-gray-900 dark:text-white">${{ order.total_amount }}</dd>
                </div>
              </dl>
            </div>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref } from "vue";
import { Link, router } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";

const props = defineProps({
  order: {
    type: Object,
    required: true,
  },
});

const orderStatuses = [
  "pending",
  "confirmed",
  "preparing",
  "ready_for_delivery",
  "out_for_delivery",
  "delivered",
  "cancelled",
];

const paymentStatuses = ["pending", "paid", "failed", "refunded"];

// Update statuses
const updateOrderStatus = () => {
  router.patch(route("admin.orders.update-status", props.order.id), {
    status: props.order.status,
  });
};

const updatePaymentStatus = () => {
  router.patch(route("admin.orders.update-payment-status", props.order.id), {
    payment_status: props.order.payment_status,
  });
};

// Utility functions
const formatStatus = (status) => {
  return status
    .split("_")
    .map((word) => word.charAt(0).toUpperCase() + word.slice(1))
    .join(" ");
};

const formatDate = (date) => {
  return new Date(date).toLocaleString("en-US", {
    year: "numeric",
    month: "short",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  });
};

const getStatusClasses = (status) => {
  const classes = {
    pending:
      "bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200",
    confirmed: "bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200",
    preparing:
      "bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-200",
    ready_for_delivery:
      "bg-indigo-100 text-indigo-800 dark:bg-indigo-900 dark:text-indigo-200",
    out_for_delivery:
      "bg-cyan-100 text-cyan-800 dark:bg-cyan-900 dark:text-cyan-200",
    delivered:
      "bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200",
    cancelled: "bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200",
  };
  return (
    classes[status] ||
    "bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200"
  );
};

const getPaymentStatusClasses = (status) => {
  const classes = {
    pending:
      "bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200",
    paid: "bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200",
    failed: "bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200",
    refunded: "bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200",
  };
  return (
    classes[status] ||
    "bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200"
  );
};
</script>
