<template>
  <AdminLayout>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-6">
      <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6 flex justify-end">
          <Link :href="route('admin.locals.create')"
            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow hover:bg-blue-700 transition">
          Local Area Create
          </Link>
        </div>
        <!-- Header -->
        <div class="mb-6">
          <h1 class="text-2xl text-center font-bold text-gray-900 dark:text-white">Locals Area Management</h1>
        </div>
        <div>
          <p v-if="flash.success" class="text-rose-800 text-center mb-4">{{ flash.success }}</p>
        </div>
        <!-- Users Table -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
              <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                  <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    ID</th>
                  <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    Name</th>
                  <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    Thana</th>
                  <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    District</th>
                  <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    Action</th>
                </tr>
              </thead>
              <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                <tr v-for="local in locals" :key="local.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">#{{ local.id
                    }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ local.name }}</td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">{{ local.area_name }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">{{ local.city_name }}
                  </td>
                  <td class="px-6 py-4 text-sm font-medium">
                    <Link :href="route('admin.locals.edit', local.id)"
                      class="inline-flex items-center px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 active:bg-indigo-200 dark:bg-indigo-900/50 dark:hover:bg-indigo-800/50 text-indigo-600 dark:text-indigo-300 rounded-md transition-colors duration-200">
                    <i class="fas fa-edit text-xs mr-1.5"></i>
                    Edit</Link>
                    <a href="#" @click.prevent="deleteLocal(local.id)"
                      class="inline-flex items-center px-3 py-1.5 bg-red-50 hover:bg-red-100 active:bg-red-200 dark:bg-red-900/50 dark:hover:bg-red-800/50 text-red-600 dark:text-red-300 rounded-md transition-colors duration-200">
                      <i class="fas fa-trash text-xs mr-1.5"></i>Delete</a>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script>
import AdminLayout from "@/Layouts/AdminLayout.vue";
import { Link, router } from "@inertiajs/vue3";
import FlashMessage from "@/Components/FlashMessage.vue";
export default {
  components: {
    AdminLayout,
    Link,
  },
  props: {
    locals: {
      type: Array,
      required: true,
    },
    flash: {
      type: Object,
      default: () => ({
        success: null,
        error: null,
      }),
    }
  },
  methods: {
    viewLocal(id) {
      // Placeholder for view action
      console.log(`View user with ID: ${id}`);
      // Example: this.$router.push(`/users/${id}`)
    },
    editLocal(id) {
      // Placeholder for edit action
      console.log(`Edit user with ID: ${id}`);
      // Example: this.$router.push(`/users/${id}/edit`)
    },
    deleteLocal(id) {
      if (confirm("Are you sure you want to delete this local?")) {
        router.delete(route("admin.locals.destroy", id), {
          preserveScroll: true,
          onSuccess: () => {
          },
          onError: (errors) => {
            console.error(errors);
          },
        });
      }
    },
  },
}
</script>
