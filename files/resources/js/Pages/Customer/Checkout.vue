<template>
  <CustomerLayout>
    <div id="modal" v-if="isPayOpen"
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 transition-opacity duration-300 ease-in-out">
      <div
        class="bg-white rounded-2xl shadow-lg p-6 w-full max-w-md transform transition-all duration-300 scale-95 animate-fadeIn text-gray-700">
        <h2 class="text-xl font-semibold mb-4 text-center">Complete Payment</h2>

        <label class="block mb-2 text-sm font-medium">Choice Payment Method</label>
        <select v-model="paymentMethod"
          class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-400">
          <option value>Choose an option</option>
          <option value="bikash">Bikash</option>
          <option value="nogod">Nogod</option>
          <option value="rocket">Rocket</option>
        </select>

        <div v-if="paymentMethod" class="mt-4">
          <p>Pay to following {{ paymentMethod }} number: 0122514244225</p>
          <div>
            <label class="block mb-2 text-sm font-medium">Enter {{ paymentMethod }} reference number:</label>
            <input type="text" v-model="paymentRef" />
          </div>
        </div>

        <!-- Close button -->
        <div class="mt-6 flex justify-end">
          <button @click="isPayOpen = false"
            class="px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600 transition">Close</button>
          <button @click="completeOrder"
            class="ml-3 px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition">Complete
            Payment</button>
        </div>
      </div>
    </div>
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Error Messages -->
      <div v-if="Object.keys(validationErrors).length > 0"
        class="mb-4 p-4 bg-red-100 dark:bg-red-900/50 border border-red-400 text-red-700 dark:text-red-300 rounded">
        <p v-for="(error, key) in validationErrors" :key="key">{{ error }}</p>
      </div>

      <div class="mb-6 flex justify-between items-center">
        <Link :href="previous_url"
          class="text-sm px-3 py-1 rounded-md bg-blue-600 text-white hover:bg-blue-700 transition-colors duration-200">
        Back
        </Link>
      </div>



      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Order Summary -->
        <div class="lg:col-span-2 space-y-6">
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <div class="flex justify-between items-center mb-4">
              <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Order Summary</h2>
              <div class="text-lg font-medium text-orange-600 dark:text-orange-400">{{ orderType === 'delivery' ?
                'Delivery Order' : 'Collection Order' }}</div>
            </div>
            <div v-if="cart.length" class="space-y-4">
              <div v-for="item in cart" :key="item.id"
                class="flex justify-between items-start border-b border-gray-200 dark:border-gray-700 pb-4">
                <div>
                  <h3 class="text-gray-900 dark:text-gray-200 font-medium">{{ item.name }}</h3>
                  <p class="text-sm text-gray-600 dark:text-gray-400">Quantity: {{ item.qty }}</p>
                  <p v-if="item.instructions" class="text-sm text-gray-600 dark:text-gray-400">Note: {{
                    item.instructions }}</p>
                </div>
                <span class="text-gray-900 dark:text-gray-200">
                  <span style="font-size: 30px;">৳</span>
                  {{
                    item.total }}
                </span>
              </div>
            </div>
            <div v-else class="text-gray-600 dark:text-gray-400 text-center py-4">Your cart is empty</div>
          </div>

          <!-- Delivery Address Section (Only for delivery orders) -->
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6" v-if="orderType === 'delivery'">
            <div class="flex justify-between items-center mb-4">
              <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100">Delivery Address</h2>
              <button @click="openAddressModal"
                class="text-orange-600 dark:text-orange-400 hover:text-orange-700 dark:hover:text-orange-300">+ Add New
                Address</button>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div v-for="address in addresses" :key="address.id" @click="selectedAddress = address"
                class="border rounded-lg p-4 cursor-pointer transition-colors duration-200" :class="[
                  selectedAddress?.id === address.id
                    ? 'border-orange-500 bg-orange-50 dark:bg-gray-700'
                    : 'border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:border-gray-300 dark:hover:border-gray-600'
                ]">
                <div class="flex items-start space-x-3">
                  <MapPinIcon class="w-5 h-5 text-gray-400 flex-shrink-0" />
                  <div>
                    <p class="text-gray-900 dark:text-gray-200 font-medium">{{ address.address_label }}</p>
                    <p class="text-sm text-gray-600 dark:text-gray-400">{{ address.address }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Collection Information Section (Only for collection orders) -->
          <div v-if="orderType != 'delivery'" class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Food Provider</h2>
            <div class="flex items-start space-x-3">
              <MapPinIcon class="w-5 h-5 text-gray-400 flex-shrink-0" />
              <div>
                <p class="text-gray-900 dark:text-gray-200 font-medium">{{ branch.name }}</p>
                <p class="text-sm text-gray-600 dark:text-gray-400">{{ branch.address }}</p>
                <p class="mt-2 text-sm text-orange-600">Please collect your order from this location</p>
              </div>
            </div>
          </div>

          <!-- Payment Method -->
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Payment Method</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <button v-for="method in ['cash', 'mobile']" :key="method" @click="selectedPaymentMethod = method"
                class="flex items-center space-x-3 border rounded-lg p-4 transition-colors duration-200" :class="[
                  selectedPaymentMethod === method
                    ? 'border-orange-500 bg-orange-50 dark:bg-gray-700'
                    : 'border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 hover:border-gray-300 dark:hover:border-gray-600'
                ]">
                <CreditCardIcon class="w-5 h-5 text-gray-400" />
                <span class="text-gray-900 dark:text-gray-200 capitalize">
                  {{ method === 'cash' ? method : 'Mobile Payment' }}
                </span>
              </button>
            </div>
          </div>

          <!-- Special Instructions (Changed from Delivery Notes) -->
          <!-- <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">{{ orderType === 'delivery' ?
              'Delivery Instructions' : 'Instructions' }}</h2>
            <textarea
              v-model="deliveryNotes"
              rows="3"
              class="w-full rounded-lg border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200 focus:ring-orange-500 focus:border-orange-500"
              :placeholder="orderType === 'delivery' ? 'Any special instructions for delivery...' : 'Any special instructions for collection...'"
            ></textarea>
          </div> -->
        </div>

        <!-- Order Total -->
        <div class="lg:col-span-1">
          <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 sticky top-4">
            <h2 class="text-xl font-semibold text-gray-900 dark:text-gray-100 mb-4">Order Total</h2>
            <div class="space-y-3">
              <div class="flex justify-between text-gray-600 dark:text-gray-300">
                <span>Subtotal</span>
                <span>
                  <span style="font-size: 30px;">৳</span>
                  {{ subtotal }}
                </span>
              </div>
              <!-- Only show delivery fee for delivery orders -->
              <div v-if="orderType === 'delivery'" class="flex justify-between text-gray-600 dark:text-gray-300">
                <span>Delivery Fee</span>
                <span>
                  <span style="font-size: 30px;">৳</span>
                  {{ deliveryFee }}
                </span>
              </div>
              <div class="border-t border-gray-200 dark:border-gray-700 pt-3">
                <div class="flex justify-between text-lg font-semibold text-gray-900 dark:text-gray-100">
                  <span>Total</span>
                  <span>
                    <span style="font-size: 30px;">৳</span>
                    {{ total }}
                  </span>
                </div>
              </div>
            </div>

            <button @click="placeOrder" :disabled="isProcessing || !cart.length"
              class="w-full mt-6 bg-orange-600 text-white py-3 px-4 rounded-lg hover:bg-orange-700 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200">{{
                isProcessing ? 'Processing...' : 'Place Order' }}</button>

            <!-- Minimum order warning if applicable -->
            <div v-if="subtotal < (branch.minimum_order || 0)"
              class="mt-3 text-sm text-red-600 dark:text-red-400 text-center">Minimum order amount is {{
                formatCurrency(branch.minimum_order) }}</div>
          </div>
        </div>
      </div>
    </div>

    <!-- New Address Modal (Only for delivery) -->
    <TransitionRoot appear :show="isAddressModalOpen" as="template">
      <Dialog as="div" @close="closeAddressModal" class="relative z-50">
        <div class="fixed inset-0 bg-black/50" />

        <div class="fixed inset-0 overflow-y-auto">
          <div class="flex min-h-full items-center justify-center p-4">
            <DialogPanel
              class="w-full max-w-md transform overflow-hidden rounded-lg bg-white dark:bg-gray-800 p-6 text-left align-middle shadow-xl transition-all">
              <DialogTitle as="h3" class="text-lg font-medium text-gray-900 dark:text-gray-100">Add New Address
              </DialogTitle>

              <div class="mt-4 space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Address
                    Label
                  </label>
                  <input type="text" v-model="newAddress.address_label"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200"
                    placeholder="Home, Work, etc." />
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                    Full
                    Address
                  </label>
                  <textarea v-model="newAddress.address" rows="3"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-200"></textarea>
                </div>

                <div class="flex items-center">
                  <input type="checkbox" v-model="newAddress.is_default"
                    class="rounded border-gray-300 dark:border-gray-600 text-orange-600 focus:ring-orange-500" />
                  <label class="ml-2 text-sm text-gray-700 dark:text-gray-300">
                    Set as default
                    address
                  </label>
                </div>
              </div>

              <div class="mt-6 flex justify-end space-x-3">
                <button @click="closeAddressModal"
                  class="px-4 py-2 text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-200">Cancel</button>
                <button @click="saveAddress"
                  class="px-4 py-2 text-sm bg-orange-600 text-white rounded-md hover:bg-orange-700 transition-colors duration-200">Save
                  Address</button>
              </div>
            </DialogPanel>
          </div>
        </div>
      </Dialog>
    </TransitionRoot>
  </CustomerLayout>
</template>


<script setup>
import { ref, computed, onMounted } from "vue";
import { Link, router } from "@inertiajs/vue3";
import {
  Dialog,
  DialogPanel,
  DialogTitle,
  TransitionRoot,
} from "@headlessui/vue";
import { CreditCardIcon, MapPinIcon } from "@heroicons/vue/24/outline";
import CustomerLayout from "@/Layouts/CustomerLayout.vue";

const props = defineProps({
  auth: Object,
  addresses: {
    type: Array,
    default: () => [],
  },
  previous_url: {
    type: String,
    default: "",
  },
  branch: {
    type: Object,
    required: true,
    validator: (value) => {
      return value && typeof value.id !== "undefined";
    },
  },
});

// State management
const isPayOpen = ref(false);
const paymentMethod = ref(null);
const paymentRef = ref("");
const cart = ref([]);
const orderType = ref(
  JSON.parse(localStorage.getItem("selectedItem")).order_type
);
const selectedAddress = ref(null);
const selectedPaymentMethod = ref("mobile");
const deliveryNotes = ref("");
const isAddressModalOpen = ref(false);
const isProcessing = ref(false);
const errors = ref({});
const validationErrors = ref({});

// Form state for new address
const newAddress = ref({
  address_label: "",
  address: "",
  latitude: null,
  longitude: null,
  is_default: false,
});

// Load cart data on component mount
onMounted(() => {
  loadCart();
  if (orderType.value === "delivery") {
    initializeAddress();
  }
});

// Initialize default address if available
const initializeAddress = () => {
  if (props.addresses?.length > 0) {
    const defaultAddress =
      props.addresses.find((addr) => addr.is_default) || props.addresses[0];
    selectedAddress.value = defaultAddress;
  }
};

// Load cart data from session storage
const loadCart = () => {
  console.log(orderType.value, "focus");

  try {
    const savedCart = localStorage.getItem("selectedItem");

    if (!savedCart) {
      redirectToMenu();
      return;
    }

    const cartData = JSON.parse(savedCart);
    console.log("Cart Data:", cartData);

    // Validate cart data structure
    if (!cartData || !cartData.items || !Array.isArray(cartData.items)) {
      console.log("Invalid cart data structure");
      sessionStorage.removeItem("foodCart");
      redirectToMenu();
      return;
    }

    // Check if cart belongs to current branch
    if (!props.branch?.id) {
      console.log("Branch data is missing");
      redirectToMenu();
      return;
    }

    if (Number(cartData.items[0].branch_id) !== Number(props.branch.id)) {
      console.log("Cart belongs to a different branch");
      redirectToMenu();
      return;
    }

    cart.value = cartData.items;
    console.log("Cart items loaded:", cart.value);

    console.log("cart itmes", cart.value);
    orderType.value = cartData.order_type;
    console.log("Order Type set to:", orderType.value);
  } catch (error) {
    console.error("Error loading cart:", error);
    showError("Error loading cart data");
    sessionStorage.removeItem("foodCart");
    redirectToMenu();
  }
};

// Helper function to redirect to menu
const redirectToMenu = () => {
  if (props.branch?.id) {
    router.visit(
      route("customer.branch.menu", {
        branch: props.branch.id,
      })
    );
  } else {
    router.visit("/");
  }
};

// Error handling helper
const showError = (message) => {
  errors.value = { message };
  setTimeout(() => {
    errors.value = {};
  }, 5000);
};

// Computed properties
const subtotal = computed(() => {
  if (!cart.value?.length) return 0;
  return cart.value.reduce((total, item) => total + Number(item.total), 0);
});

const deliveryFee = computed(() => {
  return orderType.value === "delivery" ? 2.99 : 0;
});

const total = computed(() => {
  const baseTotal = subtotal.value;
  return orderType.value === "delivery"
    ? baseTotal + deliveryFee.value
    : baseTotal;
});

// Validation functions
const validateAddress = () => {
  if (orderType.value === "delivery" && !selectedAddress.value) {
    validationErrors.value.address = "Please select a delivery address";
    return false;
  }
  return true;
};

const validatePaymentMethod = () => {
  if (!selectedPaymentMethod.value) {
    validationErrors.value.payment = "Please select a payment method";
    return false;
  }
  return true;
};

const validateCart = () => {
  if (!cart.value?.length) {
    validationErrors.value.cart = "Your cart is empty";
    return false;
  }
  return true;
};

const validateMinimumOrder = () => {
  const minimumOrder = props.branch.minimum_order || 0;
  if (subtotal.value < minimumOrder) {
    validationErrors.value.minimum = `Minimum order amount is £${minimumOrder}`;
    return false;
  }
  return true;
};

const completeOrder = () => {
  const orderData = {
    order_type: orderType.value,
    branch_id: props.branch.id,
    items: cart.value.map((item) => ({
      food_id: item.id,
      quantity: item.qty,
      special_instructions: item.instructions || "",
      order_extras: item.selected_extras || [],
      unit_price: item.base_price,
      subtotal: item.total,
    })),
    payment_method:
      selectedPaymentMethod.value === "mobile"
        ? paymentMethod.value
        : selectedPaymentMethod.value,
    paymentRef: paymentRef.value,
    subtotal: subtotal.value,
    total_amount: total.value,
  };
  validationErrors.value = {};

  // Run all validations
  const isValid =
    validateCart() &&
    validateAddress() &&
    validatePaymentMethod() &&
    validateMinimumOrder();

  if (!isValid) {
    return;
  }

  isProcessing.value = true;

  // Only add delivery-related fields if it's a delivery order
  if (orderType.value === "delivery") {
    orderData.delivery_address = selectedAddress.value?.address;
    orderData.delivery_latitude = selectedAddress.value?.latitude;
    orderData.delivery_longitude = selectedAddress.value?.longitude;
    orderData.delivery_notes = deliveryNotes.value;
    orderData.delivery_fee = deliveryFee.value;
  }

  console.log("Submitting order:", orderData);

  router.post(route("customer.orders.store"), orderData, {
    onSuccess: (page) => {
      localStorage.removeItem("selectedItem");
      const urlParts = page.url.split("/");
      console.log(urlParts);
      const orderId = urlParts[urlParts.length - 1];

      if (orderId) {
        router.visit(route("customer.orders.show", { order: orderId }));
      } else {
        showError("Failed to process order. Please try again.");
        isProcessing.value = false;
      }
    },
    onError: (e) => {
      console.error("Error:", e);
      isProcessing.value = false;
      validationErrors.value = e;
    },
  });
};

// Place order
const placeOrder = () => {
  if (selectedPaymentMethod.value != "mobile") {
    completeOrder();
  } else {
    isPayOpen.value = true;
  }
};

// Address management
const openAddressModal = () => {
  isAddressModalOpen.value = true;
};

const closeAddressModal = () => {
  isAddressModalOpen.value = false;
  newAddress.value = {
    address_label: "",
    address: "",
    latitude: null,
    longitude: null,
    is_default: false,
  };
  validationErrors.value = {};
};

const saveAddress = () => {
  router.post(route("customer.addresses.store"), newAddress.value, {
    onSuccess: () => {
      closeAddressModal();
      router.reload({ only: ["addresses"] });
    },
    onError: (errors) => {
      validationErrors.value = errors;
    },
  });
};

const setDefaultAddress = (address) => {
  router.post(
    route("customer.addresses.setDefault", { address: address.id }),
    {},
    {
      onSuccess: () => {
        router.reload({ only: ["addresses"] });
      },
    }
  );
};

const deleteAddress = (address) => {
  if (confirm("Are you sure you want to delete this address?")) {
    router.delete(route("customer.addresses.delete", { address: address.id }), {
      onSuccess: () => {
        router.reload({ only: ["addresses"] });
      },
    });
  }
};

// Format currency helper
const formatCurrency = (value) => {
  return new Intl.NumberFormat("en-BD", {
    style: "currency",
    currency: "BDT",
    minimumFractionDigits: 2,
  }).format(Number(value));
};
</script>
