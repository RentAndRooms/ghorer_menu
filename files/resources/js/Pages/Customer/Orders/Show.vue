<script setup>
import { computed } from "vue";
import OrderStatus from "@/Components/OrderStatus.vue";
import CustomerLayout from "@/Layouts/CustomerLayout.vue";
import { Link } from "@inertiajs/vue3";

const props = defineProps({
  order: Object,
  statusTimeline: Array,
});

console.log(props.order);

const deliveryEta = computed(() => {
  if (!props.order.estimated_delivery_time) return null;
  return new Date(props.order.estimated_delivery_time).toLocaleTimeString(
    "en-US",
    {
      hour: "2-digit",
      minute: "2-digit",
    }
  );
});

const getStatusIcon = (status) => {
  const icons = {
    pending: "clock",
    processing: "spinner",
    preparing: "utensils",
    ready: "check-circle",
    delivered: "flag-checkered",
    completed: "check-double",
    cancelled: "times-circle",
  };
  return icons[status.toLowerCase()] || "circle";
};
</script>

<template>
  <CustomerLayout>
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
      <!-- Back Button and Header -->
      <div class="mb-6">
        <Link :href="route('customer.orders.index')"
          class="inline-flex items-center text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200">
        <i class="fas fa-arrow-left mr-2"></i>
        Back to Orders
        </Link>
      </div>

      <!-- Order Header -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
        <div class="sm:flex sm:items-center sm:justify-between">
          <div>
            <div class="flex items-center">
              <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Order #{{ order.id }}</h1>
              <OrderStatus :status="order.status" class="ml-4" />
            </div>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Placed on {{ new
              Date(order.created_at).toLocaleDateString() }}</p>
          </div>
          <div class="mt-4 sm:mt-0">
            <div class="text-right">
              <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Amount</p>
              <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">
                <span style="font-size: 30px;">৳</span>
                {{ parseFloat(order.total_amount).toFixed(2) }}
              </p>
            </div>
          </div>
        </div>
      </div>

      <!-- Order Progress -->
      <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Order Progress</h2>
        <div class="mt-4">
          <div class="relative">
            <!-- Progress Line -->
            <!-- <div class="absolute top-5 left-1/2 w-full h-0.5 bg-gray-200 dark:bg-gray-700 z-0"
              style="transform: translateX(50%);"></div> -->

            <!-- Status Points -->
            <ul class="flex justify-between items-center w-full">
              <li v-for="(status, index) in statusTimeline" :key="status.status"
                class="flex-1 flex flex-col items-center relative">
                <!-- Connector Line -->
                <!-- <div v-if="index < statusTimeline.length - 1"
                  class="absolute top-5 left-1/2 w-full h-0.5 bg-gray-200 dark:bg-gray-700 z-0"
                  style="transform: translateX(50%);"></div> -->

                <!-- Status Icon -->
                <div :class="[
                  'h-10 w-10 rounded-full flex items-center justify-center z-10 shadow-md',
                  status.completed ? 'bg-indigo-600 dark:bg-indigo-500' : 'bg-gray-300 dark:bg-gray-600'
                ]">
                  <i :class="[
                    'fas',
                    `fa-${getStatusIcon(status.status)}`,
                    status.completed ? 'text-white' : 'text-gray-500'
                  ]"></i>
                </div>

                <!-- Status Label -->
                <p :class="[
                  'mt-2 text-sm font-semibold text-center',
                  status.completed ? 'text-gray-900 dark:text-gray-100' : 'text-gray-500 dark:text-gray-400'
                ]">
                  {{ status.label }}
                </p>

                <!-- Timestamp -->
                <p class="text-xs text-gray-500 dark:text-gray-400 text-center">
                  {{ status.timestamp ? new Date(status.timestamp).toLocaleString() : 'Pending' }}
                </p>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Order Details -->
      <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
        <!-- Delivery Information -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
          <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Delivery Information</h2>
          <dl class="mt-4 space-y-4">
            <div>
              <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Delivery Address</dt>
              <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ order.delivery_address }}</dd>
            </div>
            <div>
              <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Restaurant</dt>
              <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ order.branch?.name || 'Unknown Restaurant' }}
              </dd>
            </div>
            <div>
              <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Estimated Delivery</dt>
              <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                {{ deliveryEta || 'Not available'
                }}
              </dd>
            </div>
          </dl>
        </div>

        <!-- Payment Information -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
          <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Payment Information</h2>
          <dl class="mt-4 space-y-4">
            <div>
              <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Payment Method</dt>
              <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ order.payment_method }}</dd>
            </div>
            <div>
              <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Payment Status</dt>
              <dd class="mt-1">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" :class="{
                  'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300':
                    order.payment_status === 'paid',
                  'bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300':
                    order.payment_status === 'pending'
                }">{{ order.payment_status }}</span>
              </dd>
            </div>
          </dl>
        </div>
      </div>
      <!-- Order Items -->
      <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Order Items</h2>

        <div class="mt-4">
          <div v-if="order.items && order.items.length" class="divide-y divide-gray-200 dark:divide-gray-700">
            <div v-for="item in order.items" :key="item.id"
              class="py-4 flex flex-col sm:flex-row sm:items-center sm:justify-between">
              <!-- Item Details -->
              <div class="flex-1">
                <div class="flex items-center">
                  <div
                    class="flex-shrink-0 w-12 h-12 bg-gray-100 dark:bg-gray-700 rounded-lg flex items-center justify-center">
                    <i class="fas fa-utensils text-gray-400"></i>
                  </div>
                  <div class="ml-4">
                    <h4 class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ item.package.name }}</h4>
                    <p class="text-rose-900">{{ item.order_type }}</p>
                    <div class="mt-1 flex items-center">
                      <span class="text-sm text-gray-500 dark:text-gray-400">Quantity: {{ item.quantity }}</span>
                      <span v-if="item.extras && item.extras.length"
                        class="ml-4 text-sm text-gray-500 dark:text-gray-400">With Extras</span>
                    </div>
                  </div>
                </div>

                <!-- Extras -->
                <div v-if="item.extras && item.extras.length" class="mt-2 ml-16 space-y-1">
                  <div v-for="extra in item.extras" :key="extra.id"
                    class="flex items-center text-sm text-gray-500 dark:text-gray-400">
                    <i class="fas fa-plus text-xs mr-2"></i>
                    {{ extra.extra_option?.name || 'Unknown Extra' }}
                    <span class="ml-2 text-gray-400">(+${{ parseFloat(extra.extra_option?.base_price || 0).toFixed(2)
                    }})</span>
                  </div>
                </div>

                <!-- Special Instructions -->
                <div v-if="item.special_instructions" class="mt-2 ml-16">
                  <p class="text-sm text-gray-500 dark:text-gray-400">
                    <i class="fas fa-info-circle mr-2"></i>
                    {{ item.special_instructions }}
                  </p>
                </div>
              </div>

              <!-- Item Price -->
              <div class="mt-4 sm:mt-0 sm:ml-6">
                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                  <span style="font-size: 30px;">৳</span>
                  {{ parseFloat(item.subtotal).toFixed(2) }}
                </p>
              </div>
            </div>
          </div>

          <div v-else class="text-center py-6">
            <p class="text-gray-500 dark:text-gray-400">No items found in this order.</p>
          </div>
        </div>

        <!-- Order Summary -->
        <div class="mt-6 border-t border-gray-200 dark:border-gray-700 pt-6">
          <dl class="space-y-4">
            <div class="flex justify-between">
              <dt class="text-sm text-gray-600 dark:text-gray-400">Subtotal</dt>
              <dd class="text-sm font-medium text-gray-900 dark:text-gray-100">
                <span style="font-size: 30px;">৳</span>
                {{ parseFloat(order.subtotal).toFixed(2) }}
              </dd>
            </div>

            <!-- Delivery Fee -->
            <div class="flex justify-between">
              <dt class="text-sm text-gray-600 dark:text-gray-400">Delivery Fee</dt>
              <dd class="text-sm font-medium text-gray-900 dark:text-gray-100">
                <span style="font-size: 30px;">৳</span>
                {{ order.delivery_fee ? `${parseFloat(order.delivery_fee).toFixed(2)}` : 'N/A' }}
              </dd>
            </div>

            <!-- Tax -->
            <div class="flex justify-between">
              <dt class="text-sm text-gray-600 dark:text-gray-400">Tax</dt>
              <dd class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ order.tax ?
                `$${parseFloat(order.tax).toFixed(2)}` : 'N/A' }}</dd>
            </div>

            <div class="flex justify-between border-t border-gray-200 dark:border-gray-700 pt-4">
              <dt class="text-base font-medium text-gray-900 dark:text-gray-100">Total</dt>
              <dd class="text-base font-medium text-indigo-600 dark:text-indigo-400">
                <span style="font-size: 30px;">৳</span>
                {{ parseFloat(order.total_amount).toFixed(2) }}
              </dd>
            </div>
          </dl>
        </div>
      </div>

      <!-- Support Section -->
      <div class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
        <div class="sm:flex sm:items-center sm:justify-between">
          <div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Need Help?</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Having issues with your order? Contact our support
              team.</p>
          </div>
          <div class="mt-4 sm:mt-0">
            <button
              class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
              <i class="fas fa-headset mr-2"></i>
              Contact Support
            </button>
          </div>
        </div>
      </div>

      <!-- Reorder Section -->
      <div v-if="order.status === 'completed'" class="mt-6 bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
        <div class="sm:flex sm:items-center sm:justify-between">
          <div>
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Enjoyed your meal?</h3>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Place the same order again with one click.</p>
          </div>
          <div class="mt-4 sm:mt-0">
            <button
              class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 dark:focus:ring-offset-gray-800">
              <i class="fas fa-redo mr-2"></i>
              Reorder
            </button>
          </div>
        </div>
      </div>
    </div>
  </CustomerLayout>
</template>

<style scoped>
.pb-safe {
  padding-bottom: env(safe-area-inset-bottom);
}

/* Timeline connector line styles */
.timeline-connector {
  position: absolute;
  top: 24px;
  left: 20px;
  bottom: 0;
  width: 2px;
  background-color: #e5e7eb;
}

.dark .timeline-connector {
  background-color: #374151;
}

/* Fade animations */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* Scroll behavior */
.smooth-scroll {
  scroll-behavior: smooth;
}

/* Custom scrollbar for order items */
.order-items-container {
  scrollbar-width: thin;
  scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
}

.order-items-container::-webkit-scrollbar {
  width: 6px;
}

.order-items-container::-webkit-scrollbar-track {
  background: transparent;
}

.order-items-container::-webkit-scrollbar-thumb {
  background-color: rgba(156, 163, 175, 0.5);
  border-radius: 3px;
}

.dark .order-items-container::-webkit-scrollbar-thumb {
  background-color: rgba(156, 163, 175, 0.3);
}
</style>
