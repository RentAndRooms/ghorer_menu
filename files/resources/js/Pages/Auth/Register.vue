<template>
  <main class="min-h-screen bg-gray-50 dark:bg-gray-900">
    <!-- Home Link -->
    <Link
      :href="route('home')"
      class="absolute top-6 left-6 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 z-50"
    >
      <i class="fas fa-home text-2xl"></i>
    </Link>

    <div class="flex min-h-screen">
      <!-- Left Side - Image -->
      <div class="hidden lg:flex lg:w-1/2 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-orange-600/70 to-red-600/70 z-10"></div>
        <img
          src="/auth-bg.jpg"
          alt="Food background"
          class="absolute inset-0 w-full h-full object-cover"
        />
        <div class="relative z-20 flex flex-col justify-center px-12">
          <div class="text-white">
            <h1 class="text-4xl font-bold">Create an Account</h1>
            <p
              class="mt-4 text-lg text-white/90"
            >Join Ghorer Menu and discover amazing restaurants in your area</p>
          </div>
        </div>
      </div>

      <!-- Right Side - Form -->
      <div class="w-full lg:w-1/2 flex items-center justify-center p-8">
        <div class="w-full max-w-md">
          <!-- Logo for mobile -->
          <div class="lg:hidden text-center mb-8">
            <img src="/logo.svg" alt="Ghorer-Menu" class="h-12 mx-auto" />
          </div>

          <div class="text-center mb-8">
            <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Sign up</h2>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
              Already have an account?
              <Link
                :href="route('login')"
                class="font-medium text-orange-600 hover:text-orange-500"
              >Sign in</Link>
            </p>
          </div>

          <!-- Register Form -->
          <form @submit.prevent="submit" class="space-y-6">
            <!-- Name -->
            <div>
              <label
                for="phone"
                class="block text-sm font-medium text-gray-700 dark:text-gray-300"
              >Phone</label>
              <input
                id="phone"
                v-model="form.phone"
                type="text"
                required
                class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-800 dark:text-white"
                :class="{ 'border-red-500': form.errors.phone }"
              />
              <div
                v-if="form.errors.phone"
                class="mt-1 text-sm text-red-600 dark:text-red-400"
              >{{ form.errors.phone }}</div>
            </div>
            <!-- Password -->
            <div>
              <label
                for="password"
                class="block text-sm font-medium text-gray-700 dark:text-gray-300"
              >Password</label>
              <div class="relative">
                <input
                  id="password"
                  v-model="form.password"
                  :type="showPassword ? 'text' : 'password'"
                  required
                  class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-800 dark:text-white"
                  :class="{ 'border-red-500': form.errors.password }"
                />
                <button
                  type="button"
                  @click="showPassword = !showPassword"
                  class="absolute right-3 top-1/2 -translate-y-1/2"
                >
                  <i
                    :class="[
                      'fas',
                      showPassword ? 'fa-eye-slash' : 'fa-eye',
                      'text-gray-400 hover:text-gray-600 dark:hover:text-gray-300'
                    ]"
                  ></i>
                </button>
              </div>
              <div
                v-if="form.errors.password"
                class="mt-1 text-sm text-red-600 dark:text-red-400"
              >{{ form.errors.password }}</div>
            </div>

            <!-- Confirm Password -->
            <div>
              <label
                for="password_confirmation"
                class="block text-sm font-medium text-gray-700 dark:text-gray-300"
              >Confirm Password</label>
              <input
                id="password_confirmation"
                v-model="form.password_confirmation"
                :type="showPassword ? 'text' : 'password'"
                required
                class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-800 dark:text-white"
              />
            </div>

            <!-- Terms -->
            <div class="flex items-start">
              <div class="flex items-center h-5">
                <input
                  id="terms"
                  v-model="form.terms"
                  type="checkbox"
                  required
                  class="h-4 w-4 rounded border-gray-300 text-orange-600 focus:ring-orange-500"
                />
              </div>
              <div class="ml-3">
                <label for="terms" class="text-sm text-gray-600 dark:text-gray-400">
                  I agree to the
                  <a
                    href="#"
                    class="font-medium text-orange-600 hover:text-orange-500"
                  >Terms of Service</a>
                  and
                  <a
                    href="#"
                    class="font-medium text-orange-600 hover:text-orange-500"
                  >Privacy Policy</a>
                </label>
              </div>
            </div>

            <!-- Submit Button -->
            <button
              type="submit"
              class="w-full flex justify-center items-center px-4 py-3 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors duration-200"
              :disabled="form.processing"
            >
              <i v-if="form.processing" class="fas fa-spinner fa-spin mr-2"></i>
              Create Account
            </button>
          </form>

          <!-- Additional Info -->
          <p
            class="mt-6 text-center text-sm text-gray-500 dark:text-gray-400"
          >By signing up, you agree to receive updates and special offers from Ghorer Menu.</p>
        </div>
      </div>
    </div>
  </main>
</template>

  <script setup>
import { ref } from "vue";
import { Link, useForm } from "@inertiajs/vue3";

const showPassword = ref(false);

const form = useForm({
  phone: "",
  password: "",
  password_confirmation: "",
  terms: false,
});

const submit = () => {
  form.post(route("register"), {
    onFinish: () => {
      form.reset("password", "password_confirmation");
    },
  });
};
</script>
