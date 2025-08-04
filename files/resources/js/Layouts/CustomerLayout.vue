<template>
  <div class="min-h-screen bg-white dark:bg-gray-900">
    <!-- Navigation -->
    <nav
      class="fixed top-0 w-full bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800 z-50"
    >
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
          <!-- Logo and Brand -->
          <div class="flex items-center">
            <div class="flex-shrink-0 flex items-center">
              <Link href="/">
                <span class="text-2xl font-bold text-orange-500 dark:text-orange-400">Ghorer Menu</span>
              </Link>
            </div>
          </div>

          <!-- Desktop Navigation -->
          <div class="hidden md:flex items-center space-x-8">
            <!-- Navigation Links -->
            <div class="flex items-center space-x-6">
              <Link
                href="/locations"
                class="text-gray-700 dark:text-gray-300 hover:text-orange-500 dark:hover:text-orange-400"
              >Branches</Link>
              <Link
                href="/customer/orders"
                class="text-gray-700 dark:text-gray-300 hover:text-orange-500 dark:hover:text-orange-400"
              >Orders</Link>
            </div>

            <!-- Action Icons -->
            <div class="flex items-center space-x-4">
              <!-- Notifications -->
              <button
                class="relative p-2 text-gray-600 dark:text-gray-400 hover:text-orange-500 dark:hover:text-orange-400"
              >
                <i class="fas fa-bell text-xl"></i>
                <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-orange-500"></span>
              </button>

              <!-- Cart -->
              <!-- <button class="relative p-2 text-gray-600 dark:text-gray-400 hover:text-orange-500 dark:hover:text-orange-400">
                  <i class="fas fa-shopping-cart text-xl"></i>
                  <span v-if="cartCount > 0"
                        class="absolute -top-1 -right-1 inline-flex items-center justify-center px-2 py-1 text-xs font-bold leading-none text-white transform translate-x-1/2 -translate-y-1/2 bg-orange-500 rounded-full">
                    {{ cartCount }}
                  </span>
              </button>-->

              <!-- Theme Toggle -->
              <button
                @click="switchTheme"
                class="p-2 text-gray-600 dark:text-gray-400 hover:text-orange-500 dark:hover:text-orange-400"
              >
                <i :class="isDark ? 'fas fa-sun' : 'fas fa-moon'" class="text-xl"></i>
              </button>

              <div v-if="auth.user" class="relative">
                <button
                  @click="showProfileMenu = !showProfileMenu"
                  class="flex items-center space-x-2"
                >
                  <div
                    class="h-8 w-8 rounded-full bg-gray-200 dark:bg-gray-700 flex items-center justify-center"
                  >
                    <span
                      class="text-sm font-medium text-gray-600 dark:text-gray-300"
                    >{{ auth.user?.name?.charAt(0) }}</span>
                  </div>
                </button>

                <div
                  v-if="showProfileMenu"
                  class="absolute right-0 mt-2 w-48 rounded-md shadow-lg bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5"
                >
                  <div class="py-1">
                    <Link
                      v-if="user.role !== 'customer'"
                      :href="route('admin.dashboard')"
                      class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                    >Dashboard</Link>
                    <Link
                      v-if="user.role !== 'customer'"
                      :href="route('profile.edit')"
                      class="block px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                    >Profile</Link>
                    <button
                      @click="logout"
                      class="block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                    >Sign out</button>
                  </div>
                </div>
              </div>
              <div v-else class="flex items-center space-x-4">
                <button
                  @click="showAuthModal = true"
                  class="text-gray-600 dark:text-gray-300 hover:text-gray-900 dark:hover:text-white"
                >Sign In</button>
              </div>

              <!-- Add the Auth Modal -->
              <AuthModal v-model="showAuthModal" :redirect-url="currentPath" />
            </div>
          </div>

          <!-- Mobile menu button -->
          <div class="md:hidden flex items-center">
            <button
              @click="isMobileMenuOpen = !isMobileMenuOpen"
              class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 dark:text-gray-300 hover:text-orange-500 dark:hover:text-orange-400"
            >
              <i :class="isMobileMenuOpen ? 'fas fa-times' : 'fas fa-bars'" class="text-xl"></i>
            </button>
          </div>
        </div>
      </div>

      <!-- Mobile menu -->
      <div v-show="isMobileMenuOpen" class="md:hidden">
        <div class="px-2 pt-2 pb-3 space-y-1">
          <a
            href="#"
            class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
          >Menu</a>
          <a
            href="#"
            class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
          >Restaurants</a>
          <a
            href="#"
            class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
          >Orders</a>
          <a
            href="#"
            class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
          >Profile</a>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="pt-16 min-h-screen bg-gray-50 dark:bg-gray-900">
      <div class="w-full mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <slot></slot>
      </div>
    </main>

    <!-- Footer -->
    <footer class="border-t border-gray-200 dark:border-gray-800" id="footer">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
          <!-- About Section -->
          <div>
            <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider">About</h3>
            <div class="mt-4 space-y-4">
              <a
                href="#"
                class="block text-base text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300"
              >
                About
                Us
              </a>
              <a
                href="#"
                class="block text-base text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300"
              >Careers</a>
              <a
                href="#"
                class="block text-base text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300"
              >Blog</a>
            </div>
          </div>

          <!-- Support Section -->
          <div>
            <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Support</h3>
            <div class="mt-4 space-y-4">
              <a
                href="#"
                class="block text-base text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300"
              >
                Help
                Center
              </a>
              <a
                href="#"
                class="block text-base text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300"
              >Safety</a>
              <a
                href="#"
                class="block text-base text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300"
              >Terms</a>
            </div>
          </div>

          <!-- Delivery Section -->
          <div>
            <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Delivery</h3>
            <div class="mt-4 space-y-4">
              <a
                href="#"
                class="block text-base text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300"
              >Restaurants</a>
              <a
                href="#"
                class="block text-base text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300"
              >Pricing</a>
              <a
                href="#"
                class="block text-base text-gray-500 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-300"
              >Cities</a>
            </div>
          </div>

          <!-- Contact Section -->
          <div>
            <h3 class="text-sm font-semibold text-gray-400 uppercase tracking-wider">Contact</h3>
            <div class="mt-4 space-y-4">
              <p class="text-base text-gray-500 dark:text-gray-400">Ghorer Menu Inc.</p>
              <p class="text-base text-gray-500 dark:text-gray-400">123 Food Street</p>
              <p class="text-base text-gray-500 dark:text-gray-400">contact@ghorermenu.com</p>
            </div>
          </div>
        </div>

        <!-- Footer Bottom -->
        <div class="mt-12 border-t border-gray-200 dark:border-gray-800 pt-8">
          <p
            class="text-base text-gray-400 text-center"
          >&copy; {{ new Date().getFullYear() }} Ghorer Menu. All rights reserved.</p>
        </div>
      </div>
    </footer>
  </div>
</template>

<script setup>
import { ref, computed } from "vue";
import { Link, usePage, router } from "@inertiajs/vue3";
import AuthModal from "@/Components/Auth/AuthModal.vue";
import { switchTheme } from "@/theme";
// State
const isDark = ref(false);
const isMobileMenuOpen = ref(false);
const showProfileMenu = ref(false);
const cartCount = ref(2); // Example cart count
const showAuthModal = ref(false);
const { props } = usePage();
const user = props.auth.user;

const auth = computed(() => usePage().props.auth);
const currentPath = computed(() => window.location.pathname);

const logout = () => {
  router.post(route("logout"));
};
</script>

<style scoped>
/* Add any component-specific styles here */
#footer {
  background: #f9edea;
}
#footer h3,
a,
p {
  color: black;
}
</style>
