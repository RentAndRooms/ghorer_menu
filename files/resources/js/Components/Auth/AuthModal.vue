<template>
  <TransitionRoot appear :show="modelValue" as="template">
    <Dialog as="div" @close="closeModal" class="relative z-50">
      <!-- Backdrop -->
      <div class="fixed inset-0 bg-black/50" aria-hidden="true" />

      <!-- Modal Container -->
      <div class="fixed inset-0 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4">
          <DialogPanel
            class="w-full max-w-md transform overflow-hidden rounded-2xl bg-white dark:bg-gray-800 p-6 shadow-xl transition-all"
          >
            <form v-if="activeTab === 'login'" @submit.prevent="handleLogin">
              <h4 class="text-center">Sign In</h4>
              <div class="space-y-4">
                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Phone</label>
                  <input
                    type="text"
                    v-model="loginForm.phone"
                    required
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-orange-500 focus:ring-orange-500"
                  />
                  <div
                    v-if="loginForm.errors.phone"
                    class="mt-1 text-sm text-red-600"
                  >{{ loginForm.errors.phone }}</div>
                </div>

                <div>
                  <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">Password</label>
                  <input
                    type="password"
                    v-model="loginForm.password"
                    required
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 shadow-sm focus:border-orange-500 focus:ring-orange-500"
                  />
                  <div
                    v-if="loginForm.errors.password"
                    class="mt-1 text-sm text-red-600"
                  >{{ loginForm.errors.password }}</div>
                </div>

                <div class="flex items-center justify-between">
                  <label class="flex items-center">
                    <input
                      type="checkbox"
                      v-model="loginForm.remember"
                      class="rounded border-gray-300 text-orange-600 shadow-sm focus:border-orange-500 focus:ring-orange-500"
                    />
                    <span class="ml-2 text-sm text-gray-600 dark:text-gray-400">Remember me</span>
                  </label>

                  <a href="#" class="text-sm text-orange-600 hover:text-orange-500">Forgot password?</a>
                </div>

                <button
                  type="submit"
                  class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-orange-600 hover:bg-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500"
                  :disabled="loginForm.processing"
                >Sign In</button>
                <div class="text-center text-sm text-gray-600 dark:text-gray-400">
                  <a
                    href="/register"
                    class="text-sm text-orange-600 hover:text-orange-500"
                  >Don't have account? sign up</a>
                </div>
              </div>
            </form>
          </DialogPanel>
        </div>
      </div>
    </Dialog>
  </TransitionRoot>
</template>

<script setup>
import { ref } from "vue";
import { Dialog, DialogPanel, TransitionRoot } from "@headlessui/vue";
import { useForm } from "@inertiajs/vue3";

const props = defineProps({
  modelValue: Boolean,
  redirectUrl: {
    type: String,
    default: "",
  },
});

const emit = defineEmits(["update:modelValue"]);

const activeTab = ref("login");

const loginForm = useForm({
  phone: "",
  password: "",
  remember: false,
  redirect_url: props.redirectUrl,
});

const registerForm = useForm({
  name: "",
  email: "",
  phone: "",
  address: "",
  password: "",
  password_confirmation: "",
  terms: false,
  role: "customer", // Default role
  redirect: props.redirectPath,
});

const closeModal = () => {
  emit("update:modelValue", false);
};

const handleLogin = () => {
  loginForm.post(route("login"), {
    onSuccess: () => {
      closeModal();
    },
  });
};

const handleRegister = () => {
  registerForm.post(route("register"), {
    onSuccess: () => {
      closeModal();
    },
  });
};
</script>
