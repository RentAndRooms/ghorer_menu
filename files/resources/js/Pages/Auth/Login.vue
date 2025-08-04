<template>
    <main class="min-h-screen bg-gray-50 dark:bg-gray-900">
      <!-- Home Link -->
      <Link :href="route('home')" class="absolute top-6 left-6 text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 z-50">
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
              <h1 class="text-4xl font-bold">Welcome to Ghorer Menu</h1>
              <p class="mt-4 text-lg text-white/90">
                Experience the finest food delivery service in your area
              </p>
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
              <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Sign in</h2>
              <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                New to Ghorer Menu?
                <Link :href="route('register')" class="font-medium text-orange-600 hover:text-orange-500">
                  Create an account
                </Link>
              </p>
            </div>

            <!-- Status Message -->
            <div v-if="status" class="mb-4 p-4 bg-green-50 dark:bg-green-900/50 text-green-700 dark:text-green-300 rounded-lg">
              {{ status }}
            </div>

            <!-- Login Form -->
            <form @submit.prevent="submit" class="space-y-6">
              <div>
                <label for="phone" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                  Phone Number
                </label>
                <input
                  id="phone"
                  v-model="form.phone"
                  type="text"
                  placeholder="Enter phone Number"
                  required
                  class="mt-1 block w-full rounded-lg border-gray-300 dark:border-gray-700 shadow-sm focus:ring-2 focus:ring-orange-500 focus:border-orange-500 dark:bg-gray-800 dark:text-white"
                  :class="{ 'border-red-500': form.errors.phone }"
                />
                <div v-if="form.errors.phone" class="mt-1 text-sm text-red-600 dark:text-red-400">
                  {{ form.errors.phone }}
                </div>
              </div>

              <div>
                <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                  Password
                </label>
                <div class="relative">
                  <input
                    id="password"
                    placeholder="Enter Password"
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
                    <i :class="[
                      'fas',
                      showPassword ? 'fa-eye-slash' : 'fa-eye',
                      'text-gray-400 hover:text-gray-600 dark:hover:text-gray-300'
                    ]"></i>
                  </button>
                </div>
                <div v-if="form.errors.password" class="mt-1 text-sm text-red-600 dark:text-red-400">
                  {{ form.errors.password }}
                </div>
              </div>

              <div class="flex items-center justify-between">
                <label class="flex items-center">
                  <input
                    type="checkbox"
                    v-model="form.remember"
                    class="rounded border-gray-300 text-orange-600 shadow-sm focus:ring-orange-500"
                  />
                  <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Remember me</span>
                </label>

                <Link
                  v-if="canResetPassword"
                  :href="route('password.request')"
                  class="text-sm font-medium text-orange-600 hover:text-orange-500"
                >
                  Forgot password?
                </Link>
              </div>

              <button
                type="submit"
                class="w-full flex justify-center items-center px-4 py-3 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition-colors duration-200"
                :disabled="form.processing"
              >
                <i v-if="form.processing" class="fas fa-spinner fa-spin mr-2"></i>
                Sign in
              </button>
            </form>
          </div>
        </div>
      </div>
    </main>
  </template>

  <script setup>
  import { ref } from 'vue'
  import { Link, useForm } from '@inertiajs/vue3'

  defineProps({
    canResetPassword: Boolean,
    status: String,
  })

  const showPassword = ref(false)

  const form = useForm({
    phone: '',
    password: '',
    remember: false,
  })

  const submit = () => {
    form.post(route('login'), {
      onFinish: () => form.reset('password'),
    })
  }
  </script>
