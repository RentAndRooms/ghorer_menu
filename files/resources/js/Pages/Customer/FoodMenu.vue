<template>
  <CustomerLayout>
    <div class="flex justify-end mb-4" style="width: 85%;">
      <Link :href="route('home')"
        class="inline-flex items-center px-4 py-2 bg-[#F9733A] hover:bg-[#ea652f] text-white rounded-lg transition-colors duration-200">
      <i class="fas fa-home mr-2"></i>
      Back
      </Link>
    </div>

    <!-- Notification Component -->
    <div v-if="notification.show"
      class="fixed top-4 right-4 z-50 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg transform transition-all duration-300"
      :class="notification.show ? 'translate-x-0 opacity-100' : 'translate-x-full opacity-0'">
      <div class="flex items-center space-x-2">
        <i class="fas fa-check-circle"></i>
        <span class="font-medium">{{ notification.message }}</span>
      </div>
    </div>

    <!-- Package Selection Modal -->
    <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        <!-- Modal Header -->
        <div class="flex justify-between items-center p-6 border-b border-gray-200 dark:border-gray-700">
          <h2 class="text-2xl font-bold text-gray-900 dark:text-white">{{ selectedPackage?.name }}</h2>
          <button @click="closeModal" class="text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 text-2xl">
            ×
          </button>
        </div>

        <!-- Modal Body -->
        <div class="p-6 space-y-6">
          <!-- Package Description -->
          <div v-if="selectedPackage?.description" class="text-gray-600 dark:text-gray-400">
            {{ selectedPackage.description }}
          </div>

          <!-- Price Selection -->
          <div class="space-y-3">
            <div class="flex gap-4">
              <label class="flex items-center space-x-2 cursor-pointer">
                <input type="radio" name="modalPortion" value="full" v-model="modalData.portion"
                  class="form-radio text-indigo-600 focus:ring-indigo-500" />
                <span class="text-gray-700 dark:text-gray-300">Price (৳{{ selectedPackage?.base_price }})</span>
              </label>
            </div>
          </div>

          <!-- Foods Included -->
          <div class="space-y-3">
            <h3
              class="text-lg font-semibold text-gray-900 dark:text-gray-100 border-b-2 border-blue-400 inline-block pb-1">
              Foods Included
            </h3>
            <div class="flex flex-wrap gap-2">
              <span v-for="(food, index) in selectedPackage?.foods" :key="index"
                class="bg-blue-500 text-white px-3 py-1 rounded-full text-sm">
                {{ food.name }}
              </span>
            </div>
          </div>

          <!-- Extra Foods Selection -->
          <div class="space-y-3">
            <h3
              class="text-lg font-semibold text-gray-900 dark:text-gray-100 border-b-2 border-green-400 inline-block pb-1">
              Select Extra Foods
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 max-h-60 overflow-y-auto">
              <label v-for="extra in extrasFoods" :key="extra.id"
                class="flex items-center gap-3 p-3 border border-gray-200 dark:border-gray-600 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 cursor-pointer">
                <input type="checkbox" :value="extra.id" v-model="modalData.selected_extras"
                  class="w-4 h-4 text-green-500 rounded border-gray-300 focus:ring-green-500" />
                <div class="flex-1">
                  <span class="text-gray-700 dark:text-gray-200 font-medium">{{ extra.name }}</span>
                  <span class="text-green-600 dark:text-green-400 ml-2">(৳{{ extra.base_price }})</span>
                </div>
              </label>
            </div>
          </div>

          <!-- Quantity Selection -->
          <div class="flex items-center gap-4">
            <span class="text-lg font-medium text-gray-700 dark:text-gray-300">Quantity:</span>
            <div class="flex items-center border border-gray-300 dark:border-gray-600 rounded-lg px-2 py-1">
              <button @click="modalData.qty = Math.max(1, modalData.qty - 1)"
                class="text-indigo-600 text-xl font-bold px-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                −
              </button>
              <span class="px-4 text-gray-900 dark:text-white font-semibold">{{ modalData.qty }}</span>
              <button @click="modalData.qty++"
                class="text-indigo-600 text-xl font-bold px-2 hover:bg-gray-100 dark:hover:bg-gray-700 rounded">
                +
              </button>
            </div>
          </div>

          <!-- Total Price Display -->
          <div class="bg-gray-50 dark:bg-gray-700 p-4 rounded-lg">
            <div class="flex justify-between items-center text-lg">
              <span class="font-medium text-gray-700 dark:text-gray-300">Total Price:</span>
              <span class="font-bold text-2xl text-indigo-600 dark:text-indigo-400">৳{{ modalTotal }}</span>
            </div>
          </div>
        </div>

        <!-- Modal Footer -->
        <div class="flex gap-3 p-6 border-t border-gray-200 dark:border-gray-700">
          <button @click="closeModal"
            class="flex-1 px-4 py-2 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
            Cancel
          </button>
          <button @click="addToCart"
            class="flex-1 px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors font-medium">
            Add to Cart
          </button>
        </div>
      </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6">
      <!-- Branch Info Header -->
      <div class="bg-white dark:bg-gray-800 rounded-lg shadow-sm p-6">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
          <div class="flex-1">
            <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">{{ branch.name }}</h1>
            <div class="mt-2 flex flex-wrap items-center gap-4">
              <span class="inline-flex items-center text-gray-600 dark:text-gray-400">
                <i class="fas fa-map-marker-alt mr-2"></i>
                {{ branch.address }}
              </span>
              <span class="inline-flex items-center text-gray-600 dark:text-gray-400">
                <i class="fas fa-phone mr-2"></i>
                {{ branch.contact_number }}
              </span>
            </div>
          </div>

          <div class="mt-4 lg:mt-0">
            <div class="flex flex-col items-end">
              <label for="orderType" class="mb-1 text-sm text-gray-700 dark:text-gray-300 font-medium">Order
                Type</label>
              <select id="orderType" v-model="orderType" @change="saveToLocalStorage"
                class="w-48 px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="delivery">Delivery</option>
                <option value="collection">Collection</option>
              </select>
            </div>
          </div>
        </div>
      </div>

      <div class="min-h-screen bg-gray-50 dark:bg-gray-900 w-full">
        <div class="container mx-auto px-4 py-8">
          <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
            <!-- Categories Sidebar -->
            <aside class="lg:col-span-1">
              <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg sticky top-24 p-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Menu Categories</h2>
                <nav class="space-y-2">
                  <button v-for="category in categories" :key="category.id" @click="scrollToCategory(category.id)"
                    class="w-full text-left px-4 py-3 rounded-lg transition-all duration-200 flex justify-between items-center text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                    :class="{ 'bg-indigo-100 dark:bg-indigo-900/30 text-indigo-600 dark:text-indigo-300': activeCategory === category.id }">
                    <span class="font-medium">{{ category.name }}</span>
                    <span class="text-sm bg-indigo-50 dark:bg-indigo-900/20 px-2.5 py-1 rounded-full">{{
                      getFoodsByCategory(category.id).length }}</span>
                  </button>
                </nav>
              </div>
            </aside>

            <!-- Food Items Grid -->
            <main class="lg:col-span-2 rounded-2xl bg-white dark:bg-gray-900 shadow-md">
              <section v-for="category in categories" :key="category.id" :id="`category-${category.id}`"
                class="scroll-mt-24 mb-8">
                <div class="dark:bg-gray-800 text-center rounded-t-2xl">
                  <h2 class="text-3xl font-bold text-gray-900 dark:text-white">{{ category.name }}</h2>
                  <p class="text-gray-600 dark:text-gray-400 leading-relaxed">{{ category.description }}</p>
                </div>

                <div class="grid grid-cols-1 gap-6">
                  <article v-for="food in getFoodsByCategory(category.id)" :key="food.id"
                    class="bg-white dark:bg-gray-800 shadow-md overflow-hidden hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 rounded-b-2xl">
                    <div class="p-6">
                      <div class="flex justify-between items-start mb-4">
                        <div>
                          <h3
                            class="text-xl font-semibold text-gray-900 text-white bg-[#F9733A] rounded-lg px-2 dark:text-white">
                            {{ food.name }}</h3>
                        </div>
                        <div>
                          <i class="fas fa-clock w-5 text-indigo-500"></i>
                          <span>Prep time: {{ food.preparation_time }} mins</span>
                        </div>
                      </div>

                      <div class="mb-4">
                        <p
                          class="text-lg font-semibold text-gray-800 dark:text-gray-100 mb-2 border-b-2 border-blue-400 inline-block pb-1">
                          Foods Included
                        </p>
                        <ul class="flex gap-2">
                          <li v-for="(item, index) in food.foods" :key="index"
                            class="bg-blue-400 text-white px-4 rounded-xl shadow-md hover:bg-blue-500 transition">
                            {{ item.name }}
                          </li>
                        </ul>
                      </div>

                      <div class="flex justify-between items-center border-t border-gray-200 dark:border-gray-700 pt-4">
                        <span class="text-xl font-bold text-gray-900 dark:text-white">৳{{ food.base_price }}</span>
                        <button @click="openFoodModal(food)"
                          class="bg-indigo-600 px-5 py-2.5 rounded-lg text-white hover:bg-indigo-700">Add To
                          Cart</button>
                      </div>
                    </div>
                  </article>
                </div>
              </section>
            </main>

            <!-- Cart Section -->
            <div class="lg:col-span-2">
              <div v-for="(item, fIndex) in selectedFood" :key="item.id"
                :class="['bg-white dark:bg-gray-800 rounded-2xl shadow-lg top-24 p-6', fIndex > 0 ? 'mt-2' : '']">
                <div class="relative flex">
                  <div>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white mb-2">{{ item.name }}</h2>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">{{ item.description }}</p>
                  </div>
                  <button @click="deleteItem(item.id)"
                    class="absolute top-0 right-0 text-2xl text-white bg-red-600 hover:bg-red-700 rounded-full w-7 h-7 flex items-center justify-center shadow-lg"
                    title="Remove">×</button>
                </div>

                <div class="mb-4">
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Price</label>
                  <div class="flex gap-4">
                    <label class="flex items-center space-x-2 text-sm text-gray-700 dark:text-gray-300">
                      <input type="radio" :name="`portion-${item.id}`" value="full" class="form-radio text-indigo-600"
                        v-model="item.portion" @change="updateItemTotal(item.id)" checked />
                      <span>(৳{{ item.base_price }})</span>
                    </label>
                    <label v-if="item.half_price"
                      class="flex items-center space-x-2 text-sm text-gray-700 dark:text-gray-300">
                      <input type="radio" :name="`portion-${item.id}`" value="half" class="form-radio text-indigo-600"
                        v-model="item.portion" @change="updateItemTotal(item.id)" />
                      <span>Half (৳{{ item.half_price }})</span>
                    </label>
                  </div>
                </div>

                <div class="flex max-w-md mx-auto">
                  <div class="p-6 rounded-2xl bg-white dark:bg-gray-900">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Food Included</h3>
                    <ul class="flex flex-wrap items-center gap-1">
                      <li v-for="(cartFood, index) in item.foods" :key="index"
                        class="px-1 text-sm rounded-full bg-rose-500/90 text-white">
                        {{ cartFood.name }}
                      </li>
                    </ul>
                  </div>
                  <div class="p-6 rounded-2xl bg-white dark:bg-gray-900">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Selected Extras</h3>
                    <ul class="flex flex-wrap items-center gap-2">
                      <li v-for="(extraId, index) in item.selected_extras" :key="index"
                        class="px-3 py-1 text-sm rounded-full bg-green-500/90 text-white">
                        {{ getExtraName(extraId) }} (৳{{ getExtraPrice(extraId) }})
                      </li>
                      <li v-if="!item.selected_extras.length" class="text-sm text-gray-500 dark:text-gray-400">
                        No extras selected
                      </li>
                    </ul>
                  </div>
                </div>

                <div class="flex items-center gap-3 mb-4">
                  <span class="text-sm text-gray-600 dark:text-gray-300">Quantity</span>
                  <div class="flex items-center border rounded-lg px-2 py-1 w-fit">
                    <button @click="decrement(item.id)" class="text-indigo-600 text-xl font-bold px-2">−</button>
                    <span class="px-2 text-gray-900 dark:text-white">{{ item.qty }}</span>
                    <button @click="increment(item.id)" class="text-indigo-600 text-xl font-bold px-2">+</button>
                  </div>
                </div>

                <div class="max-w-md mx-auto bg-white rounded-2xl">
                  <h2 class="text-xl font-semibold mb-4 pb-2">Details</h2>

                  <div class="grid grid-cols-3 border-b font-medium text-gray-700">
                    <div>Item</div>
                    <div class="text-center">Qty</div>
                    <div class="text-right">Price</div>
                  </div>
                  <div class="grid grid-cols-3 font-medium text-gray-700">
                    <div>Package</div>
                    <div class="text-center">{{ item.qty }}</div>
                    <div class="text-right">৳ {{ getItemPackagePrice(item) * item.qty }}</div>
                  </div>
                  <div v-for="extraId in item.selected_extras" :key="extraId"
                    class="grid grid-cols-3 font-medium text-gray-700">
                    <div>{{ getExtraName(extraId) }}</div>
                    <div class="text-center">{{ item.qty }}</div>
                    <div class="text-right">৳ {{ getExtraPrice(extraId) * item.qty }}</div>
                  </div>
                  <div class="grid grid-cols-3 border-t font-medium text-gray-700">
                    <div class="col-span-2 font-semibold">Sub Total</div>
                    <div class="text-right">৳ {{ item.total }}</div>
                  </div>
                </div>
              </div>

              <div v-if="selectedFood.length > 0">
                <div class="mt-4 p-4 bg-white dark:bg-gray-800 rounded-2xl shadow-md text-center">
                  <span class="text-lg font-semibold text-gray-700 dark:text-gray-300">Total:</span>
                  <span class="text-xl font-bold text-indigo-600 dark:text-indigo-400 ml-2">৳ {{ subTotal }}</span>
                </div>

                <button @click="handleCheckout" :disabled="isProcessing"
                  class="w-full text-lg bg-indigo-600 text-white hover:bg-indigo-700 text-center px-5 py-2.5 rounded-lg transition-colors duration-200 flex justify-center items-center mt-2">
                  <template v-if="isProcessing">
                    <svg class="animate-spin h-5 w-5 text-white text-center" xmlns="http://www.w3.org/2000/svg"
                      fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" />
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                    </svg>
                  </template>
                  <template v-else>Proceed To Checkout</template>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </CustomerLayout>
</template>

<script setup>
import { ref, computed, watch } from "vue";
import { Link, router } from "@inertiajs/vue3";
import CustomerLayout from "@/Layouts/CustomerLayout.vue";

const props = defineProps({
  branch: { type: Object, required: true },
  categories: { type: Array, required: true },
  packages: { type: Array, required: true },
  extrasFoods: { type: Array, required: true },
  auth: { type: Object, default: () => ({}) },
});

const isProcessing = ref(false);
const activeCategory = ref(null);
const orderType = ref("collection");
const selectedFood = ref(
  JSON.parse(localStorage.getItem("selectedItem"))?.items?.map(item => ({
    ...item,
    selected_extras: item.selected_extras || [],
    base_price: parseFloat(item.base_price) || 0,
    half_price: item.half_price ? parseFloat(item.half_price) : null,
    total: parseFloat(item.total) || 0,
    portion: item.portion || 'full'
  })) ?? []
);

// Modal state
const showModal = ref(false);
const selectedPackage = ref(null);
const modalData = ref({
  portion: 'full',
  selected_extras: [],
  qty: 1
});

// Notification state
const notification = ref({
  show: false,
  message: '',
});

// Store timeout ID to clear it when needed
let notificationTimeout = null;

const showNotification = (message, duration = 3000) => {
  // Clear any existing timeout to prevent overlap
  if (notificationTimeout) {
    clearTimeout(notificationTimeout);
  }

  // Reset notification state
  notification.value = {
    show: true,
    message: message
  };

  // Set new timeout to hide notification
  notificationTimeout = setTimeout(() => {
    notification.value.show = false;
    notification.value.message = '';
    notificationTimeout = null;
  }, duration);
};

const modalTotal = computed(() => {
  if (!selectedPackage.value) return 0;

  const basePrice = modalData.value.portion === 'half' && selectedPackage.value.half_price
    ? parseFloat(selectedPackage.value.half_price)
    : parseFloat(selectedPackage.value.base_price);

  const extrasTotal = modalData.value.selected_extras.reduce((sum, extraId) => {
    const extra = props.extrasFoods.find(opt => opt.id === extraId);
    return sum + (extra ? parseFloat(extra.base_price) : 0);
  }, 0);

  return (basePrice + extrasTotal) * modalData.value.qty;
});

const openFoodModal = (food) => {
  selectedPackage.value = food;
  modalData.value = {
    portion: 'full',
    selected_extras: [],
    qty: 1
  };
  showModal.value = true;
};

const closeModal = () => {
  showModal.value = false;
  selectedPackage.value = null;
  modalData.value = {
    portion: 'full',
    selected_extras: [],
    qty: 1
  };
};

const addToCart = () => {
  if (!selectedPackage.value) return;

  const basePrice = parseFloat(selectedPackage.value.base_price) || 0;
  const halfPrice = selectedPackage.value.half_price ? parseFloat(selectedPackage.value.half_price) : null;

  // Create a unique identifier for the item including selected extras
  const itemSignature = `${selectedPackage.value.id}-${modalData.value.portion}-${JSON.stringify(modalData.value.selected_extras.sort())}`;

  // Check if exact same item (same package, portion, and extras) already exists
  const existingItemIndex = selectedFood.value.findIndex(item => {
    const existingSignature = `${item.id}-${item.portion}-${JSON.stringify((item.selected_extras || []).sort())}`;
    return existingSignature === itemSignature;
  });

  if (existingItemIndex !== -1) {
    // Update existing item quantity
    selectedFood.value[existingItemIndex].qty += modalData.value.qty;
    selectedFood.value[existingItemIndex].total = calculateItemTotal(selectedFood.value[existingItemIndex]);
    showNotification(`${selectedPackage.value.name} quantity updated! (${selectedFood.value[existingItemIndex].qty})`);
  } else {
    // Add new item
    const newItem = {
      ...selectedPackage.value,
      qty: modalData.value.qty,
      portion: modalData.value.portion,
      selected_extras: [...modalData.value.selected_extras],
      base_price: basePrice,
      half_price: halfPrice,
      total: modalTotal.value
    };
    selectedFood.value.push(newItem);
    showNotification(`${selectedPackage.value.name} added to cart!`);
  }

  // Log for debugging
  console.log('Added/Updated item:', selectedFood.value);

  // Save to localStorage and close modal with slight delay to ensure state updates
  saveToLocalStorage();
  setTimeout(closeModal, 100);
};

const calculateItemTotal = (item) => {
  const basePrice = item.portion === 'half' && item.half_price
    ? parseFloat(item.half_price)
    : parseFloat(item.base_price);

  const extrasTotal = item.selected_extras.reduce((sum, extraId) => {
    const extra = props.extrasFoods.find(opt => opt.id === extraId);
    return sum + (extra ? parseFloat(extra.base_price) : 0);
  }, 0);

  return (basePrice + extrasTotal) * item.qty;
};

const getItemPackagePrice = (item) => {
  return item.portion === 'half' && item.half_price ? item.half_price : item.base_price;
};

const getExtraName = (id) => {
  const extra = props.extrasFoods.find(e => e.id === id);
  return extra ? extra.name : 'Unknown';
};

const getExtraPrice = (id) => {
  const extra = props.extrasFoods.find(e => e.id === id);
  return extra ? parseFloat(extra.base_price) : 0;
};

const updateItemTotal = (itemId) => {
  const item = selectedFood.value.find(i => i.id === itemId);
  if (item) {
    item.total = calculateItemTotal(item);
    saveToLocalStorage();
  }
};

const subTotal = computed(() => {
  return selectedFood.value.reduce((sum, item) => sum + item.total, 0);
});

const deleteItem = (id) => {
  selectedFood.value = selectedFood.value.filter(item => item.id !== id);
  saveToLocalStorage();
};

const handleCheckout = () => {
  if (!selectedFood.value.length) return;
  isProcessing.value = true;
  setTimeout(() => {
    router.visit(route("customer.checkout", { branch: props.branch.id }));
  }, 1000);
};

const saveToLocalStorage = () => {
  localStorage.setItem("selectedItem", JSON.stringify({
    items: selectedFood.value,
    sub_total: subTotal.value,
    order_type: orderType.value,
    timestamp: new Date().toISOString()
  }));
};

const increment = (itemId) => {
  const item = selectedFood.value.find(item => item.id === itemId);
  if (item) {
    item.qty++;
    item.total = calculateItemTotal(item);
    saveToLocalStorage();
  }
};

const decrement = (itemId) => {
  const item = selectedFood.value.find(item => item.id === itemId);
  if (item && item.qty > 1) {
    item.qty--;
    item.total = calculateItemTotal(item);
    saveToLocalStorage();
  }
};

const getFoodsByCategory = (categoryId) => {
  return props.packages.filter(pack => pack.category_id == categoryId);
};

const scrollToCategory = (categoryId) => {
  activeCategory.value = categoryId;
  const element = document.getElementById(`category-${categoryId}`);
  if (element) {
    element.scrollIntoView({ behavior: "smooth" });
  }
};

watch(selectedFood, () => {
  saveToLocalStorage();
}, { deep: true });
</script>
<style scoped>
/* Notification Styles */
.notification-enter-active,
.notification-leave-active {
  transition: all 0.3s ease;
}

.notification-enter-from,
.notification-leave-to {
  transform: translateX(100%);
  opacity: 0;
}

/* Scroll Margin for Header Offset */
.scroll-mt-20 {
  scroll-margin-top: 5rem;
}

/* Smooth Scrolling */
:deep(html) {
  scroll-behavior: smooth;
}

/* Custom Scrollbar Styling */
.scrollbar-thin {
  scrollbar-width: thin;
  scrollbar-color: rgba(156, 163, 175, 0.5) transparent;
}

.scrollbar-thin::-webkit-scrollbar {
  width: 6px;
}

.scrollbar-thin::-webkit-scrollbar-track {
  background: transparent;
}

.scrollbar-thin::-webkit-scrollbar-thumb {
  background-color: rgba(156, 163, 175, 0.5);
  border-radius: 3px;
}

.dark .scrollbar-thin::-webkit-scrollbar-thumb {
  background-color: rgba(156, 163, 175, 0.3);
}

/* Category Navigation Hover Effects */
.category-link {
  position: relative;
  transition: all 0.3s ease;
}

.category-link::after {
  content: "";
  position: absolute;
  bottom: -2px;
  left: 0;
  width: 0;
  height: 2px;
  background-color: rgb(79, 70, 229);
  transition: width 0.3s ease;
}

.dark .category-link::after {
  background-color: rgb(129, 140, 248);
}

.category-link:hover::after {
  width: 100%;
}

/* Card Hover Effects */
.food-card {
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.food-card:hover {
  transform: translateY(-2px);
}

/* Modal Transitions */
.modal-enter-active,
.modal-leave-active {
  transition: opacity 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
}

/* Fade Transitions */
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}

/* Slide Transitions */
.slide-up-enter-active,
.slide-up-leave-active {
  transition: transform 0.3s ease-out;
}

.slide-up-enter-from,
.slide-up-leave-to {
  transform: translateY(100%);
}

/* Cart Preview Animations */
.cart-preview-enter-active,
.cart-preview-leave-active {
  transition: all 0.3s ease-out;
}

.cart-preview-enter-from,
.cart-preview-leave-to {
  transform: translateY(100%);
  opacity: 0;
}

/* List Transitions for Cart Items */
.list-move,
.list-enter-active,
.list-leave-active {
  transition: all 0.3s ease;
}

.list-enter-from,
.list-leave-to {
  opacity: 0;
  transform: translateX(-30px);
}

.list-leave-active {
  position: absolute;
}

/* Image Loading Skeleton */
.image-skeleton {
  @apply bg-gray-200 dark:bg-gray-700 animate-pulse;
}

/* Custom Button Styles */
.btn-primary {
  @apply inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200;
}

.dark .btn-primary {
  @apply focus:ring-offset-gray-900;
}

.btn-secondary {
  @apply inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200;
}

.dark .btn-secondary {
  @apply border-gray-600 text-gray-300 bg-gray-800 hover:bg-gray-700 focus:ring-offset-gray-900;
}

/* Price Badge */
.price-badge {
  @apply inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800;
}

.dark .price-badge {
  @apply bg-green-900/50 text-green-300;
}

/* Status Indicators */
.status-indicator {
  @apply inline-block w-2 h-2 rounded-full;
}

.status-indicator.available {
  @apply bg-green-500;
}

.status-indicator.unavailable {
  @apply bg-red-500;
}

/* Food Card Image Container */
.food-image-container {
  position: relative;
  padding-top: 66.67%;
  /* 3:2 Aspect Ratio */
  overflow: hidden;
}

.food-image-container img {
  position: absolute;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  object-fit: cover;
  transition: transform 0.3s ease;
}

.food-image-container:hover img {
  transform: scale(1.05);
}

/* Custom Checkbox Style */
.custom-checkbox {
  @apply form-checkbox rounded border-gray-300 text-indigo-600 focus:ring-indigo-500;
}

.dark .custom-checkbox {
  @apply border-gray-600 text-gray-400 bg-gray-800 focus:ring-offset-gray-900 focus:ring-gray-600;
}

/* Quantity Input Styles */
.quantity-input {
  @apply w-16 text-center border-gray-300 rounded-md focus:ring-indigo-500 focus:border-indigo-500;
}

.dark .quantity-input {
  @apply border-gray-600 bg-gray-800 text-gray-300;
}

/* Special Instructions Textarea */
.special-instructions {
  @apply block w-full rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm;
}

.dark .special-instructions {
  @apply border-gray-600 bg-gray-800 text-gray-300;
}

/* Mobile Optimizations */
@media (max-width: 640px) {
  .food-grid {
    grid-template-columns: repeat(1, minmax(0, 1fr));
  }

  .cart-preview {
    padding-bottom: env(safe-area-inset-bottom);
  }
}

/* Print Styles */
@media print {
  .no-print {
    display: none;
  }

  .print-only {
    display: block;
  }
}

/* Accessibility */
@media (prefers-reduced-motion: reduce) {

  *,
  *::before,
  *::after {
    animation-duration: 0.01ms !important;
    animation-iteration-count: 1 !important;
    transition-duration: 0.01ms !important;
    scroll-behavior: auto !important;
  }
}
</style>