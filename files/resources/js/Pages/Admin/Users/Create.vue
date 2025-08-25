<template>
  <AdminLayout>
    <div class="bg-white min-h-screen p-8">
      <h2 class="text-center text-xl text-gray-800">Create User</h2>
      <form @submit.prevent="handleSubmit" class="max-w-4xl mx-auto text-gray-800">
        <!-- Username -->
        <div class="mb-6">
          <label for="name" class="block mb-2 text-sm font-medium text-gray-800">Name</label>
          <input id="name" v-model="formData.name" type="text"
            class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            placeholder="Enter name" required />
          <p v-if="formData.errors.name" class="text-red-500 text-sm mt-1">{{ formData.errors.name }}</p>
        </div>

        <!-- Phone -->
        <div class="mb-6">
          <label for="phone" class="block mb-2 text-sm font-medium text-gray-800">Phone</label>
          <input id="phone" v-model="formData.phone" type="tel"
            class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            placeholder="Enter phone number" required />
          <p v-if="formData.errors.phone" class="text-red-500 text-sm mt-1">{{ formData.errors.phone }}</p>
        </div>

        <!-- Password -->
        <div class="mb-6">
          <label for="password" class="block mb-2 text-sm font-medium text-gray-800">Password</label>
          <input id="password" type="password" v-model="formData.password"
            class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
            placeholder="Enter password" required />
          <p v-if="formData.errors.password" class="text-red-500 text-sm mt-1">{{ formData.errors.password }}</p>
        </div>

        <!-- Role Selection -->
        <div class="flex flex-col sm:flex-row gap-6 mb-8">
          <!-- Consumer Option -->
          <label class="flex items-center gap-3 cursor-pointer select-none">
            <div class="relative">
              <input type="radio" v-model="formData.role" value="customer" name="role" class="sr-only" required />
              <div
                class="w-5 h-5 border-2 border-gray-400 rounded flex items-center justify-center transition-all duration-200"
                :class="formData.role === 'customer' ? 'bg-green-500 border-green-500' : 'bg-transparent'">
                <svg v-if="formData.role === 'customer'" class="w-3 h-3 text-white" fill="none" stroke="currentColor"
                  viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
              </div>
            </div>
            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M15.75 6.75a3 3 0 11-6 0 3 3 0 016 0zM4.5 20.25a8.25 8.25 0 0115 0" />
            </svg>
            <span class="text-sm text-gray-800">Consumer</span>
          </label>

          <!-- Partner Option -->
          <label class="flex items-center gap-3 cursor-pointer select-none">
            <div class="relative">
              <input type="radio" v-model="formData.role" value="branch_admin" name="role" class="sr-only" />
              <div
                class="w-5 h-5 border-2 border-gray-400 rounded flex items-center justify-center transition-all duration-200"
                :class="formData.role === 'branch_admin' ? 'bg-blue-500 border-blue-500' : 'bg-transparent'">
                <svg v-if="formData.role === 'branch_admin'" class="w-3 h-3 text-white" fill="none"
                  stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                </svg>
              </div>
            </div>
            <svg class="w-6 h-6 text-gray-700" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M17 20h5v-2a4 4 0 00-5-3.87M9 20H4v-2a4 4 0 015-3.87M15 11a3 3 0 100-6 3 3 0 000 6zM9 11a3 3 0 100-6 3 3 0 000 6z" />
            </svg>
            <span class="text-sm text-gray-800">Partner</span>
          </label>

          <!-- Restaurant Dropdown -->
          <div v-show="formData.role === 'branch_admin'">
            <select id="rest_id" v-model="formData.rest_id"
              class="mt-1 block w-full rounded-md border-gray-300 shadow-sm bg-gray-100 text-gray-800" required>
              <option value="">Select a Restaurant</option>
              <option v-for="rest in Restaurant" :key="rest.id" :value="rest.id">{{ rest.name }}</option>
            </select>
            <InputError :message="formData.errors.rest_id" class="mt-2" />
          </div>
        </div>
        <p v-if="formData.errors.role" class="text-red-500 text-sm mt-1">{{ formData.errors.role }}</p>

        <!-- Submit Button -->
        <div class="pt-6 border-t border-gray-300">
          <button type="submit"
            class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-8 rounded-lg transition-all duration-200 hover:shadow-lg hover:shadow-blue-500/25 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-slate-700"
            :disabled="formData.processing">
            Create User
          </button>
        </div>
      </form>
    </div>
  </AdminLayout>
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { useForm } from '@inertiajs/vue3';

export default {
  name: 'UserForm',
  components: {
    AdminLayout,
  },
  props: {
    Restaurant: {
      type: Array,
      required: true,
    },
  },
  setup() {
    const formData = useForm({
      name: '',
      password: '',
      phone: '',
      role: '',
      rest_id: '', // Added rest_id to formData
    });

    return { formData };
  },
  methods: {
    handleSubmit() {
      this.formData.post(route('admin.user.store'), {
        onSuccess: () => {
          console.log('User created successfully');
          this.resetForm();
        },
        onError: (errors) => {
          console.error('Form submission failed:', errors);
        },
      });
    },
    resetForm() {
      this.formData.reset();
    },
  },
};
</script>