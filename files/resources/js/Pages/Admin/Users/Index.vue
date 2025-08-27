<template>
  <AdminLayout>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-6">
      <div class="container mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-end mb-6">
          <Link :href="route('admin.user.create')"
            class="px-6 py-2 bg-rose-600 hover:bg-rose-700 text-white font-semibold rounded-lg shadow-md transition duration-300 ease-in-out transform hover:scale-105">
          Create User
          </Link>
        </div>

        <!-- Header -->
        <div class="mb-6">
          <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Users</h1>
        </div>

        <!-- Users Table -->
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
              <thead class="bg-gray-50 dark:bg-gray-700">
                <tr>
                  <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    ID
                  </th>
                  <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    Name
                  </th>
                  <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    Phone
                  </th>
                  <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    Role
                  </th>
                  <th scope="col"
                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                    Action
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                <tr v-for="user in users" :key="user.id" class="hover:bg-gray-50 dark:hover:bg-gray-700">
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                    #{{ user.id }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-white">
                    {{ user.name }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                    {{ user.phone }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm px-2 py-1 rounded-full font-medium" :class="getRoleClasses(user.role)">
                      {{ formatRole(user.role) }}
                    </span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <a href="#" @click.prevent="viewUser(user.id)" class="inline-flex items-center px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 active:bg-indigo-200 
           dark:bg-indigo-900/50 dark:hover:bg-indigo-800/50 text-indigo-600 dark:text-indigo-300 
           rounded-md transition-colors duration-200 mr-2">
                      <i class="fa-solid fa-eye text-xs mr-1.5"></i>
                      View
                    </a>

                    <a href="#" @click.prevent="editUser(user.id)" class="inline-flex items-center px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 active:bg-indigo-200 
           dark:bg-indigo-900/50 dark:hover:bg-indigo-800/50 text-indigo-600 dark:text-indigo-300 
           rounded-md transition-colors duration-200 mr-2">
                      <i class="fas fa-edit text-xs mr-1.5"></i>
                      Edit
                    </a>

                    <a href="#" @click.prevent="deleteUser(user.id)" class="inline-flex items-center px-3 py-1.5 bg-red-50 hover:bg-red-100 active:bg-red-200 
           dark:bg-red-900/50 dark:hover:bg-red-800/50 text-red-600 dark:text-red-300 
           rounded-md transition-colors duration-200">
                      <i class="fas fa-trash text-xs mr-1.5"></i>
                      Delete
                    </a>
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
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Link } from '@inertiajs/vue3'
export default {
  components: {
    AdminLayout,
    Link
  },
  props: {
    users: {
      type: Array,
      required: true
    }
  },
  methods: {
    formatRole(role) {
      return role.split('_').map(word =>
        word.charAt(0).toUpperCase() + word.slice(1)
      ).join(' ')
    },
    getRoleClasses(role) {
      const classes = {
        admin: 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
        user: 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
        guest: 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200'
      }
      return classes[role] || 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200'
    },
    viewUser(id) {
      this.$inertia.get(route('admin.user.show', id));
    },
    editUser(id) {
      this.$inertia.get(route('admin.user.edit', id));
    },
    deleteUser(id) {
      if (confirm('Are you sure you want to delete this user?')) {
        this.$inertia.delete(route('admin.user.destroy', id));
      }
    }
  }
}
</script>
