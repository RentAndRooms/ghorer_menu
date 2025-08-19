<template>
  <AdminLayout>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-6">
      <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-6 flex justify-end">
          <Link :href="route('admin.locals.create')"
            class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow hover:bg-blue-700 transition">
          Local Create
          </Link>
        </div>
        <!-- Header -->
        <div class="mb-6">
          <h1 class="text-2xl text-center font-bold text-gray-900 dark:text-white">Locals Area Management</h1>
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
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <a href="#" @click.prevent="viewLocal(local.id)"
                      class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">View</a>
                    <a href="#" @click.prevent="editLocal(local.id)"
                      class="ml-4 text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">Edit</a>
                    <a href="#" @click.prevent="deleteLocal(local.id)"
                      class="ml-4 text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">Delete</a>
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
import { Link } from "@inertiajs/vue3";
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
      // Placeholder for delete action
      if (confirm("Are you sure you want to delete this user?")) {
        console.log(`Delete user with ID: ${id}`);
        // Example: this.$inertia.delete(`/users/${id}`)
      }
    },
  },
};
</script>
