<template>
  <AdminLayout>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 py-6">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8">
         <div class="mb-6">
        <Link :href="route('admin.user.create')" class="text-2xl font-bold text-gray-900 dark:text-white">Users Create</Link> 
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
                  <span class="text-sm px-2 py-1 rounded-full font-medium"
                    :class="getRoleClasses(user.role)">
                    {{ formatRole(user.role) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                  <a href="#" @click.prevent="viewUser(user.id)"
                    class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">
                    View
                  </a>
                  <a href="#" @click.prevent="editUser(user.id)"
                    class="ml-4 text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">
                    Edit
                  </a>
                  <a href="#" @click.prevent="deleteUser(user.id)"
                    class="ml-4 text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300">
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
  components : {
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
      // Placeholder for view action
      console.log(`View user with ID: ${id}`)
      // Example: this.$router.push(`/users/${id}`)
    },
    editUser(id) {
      // Placeholder for edit action
      console.log(`Edit user with ID: ${id}`)
      // Example: this.$router.push(`/users/${id}/edit`)
    },
    deleteUser(id) {
      // Placeholder for delete action
      if (confirm('Are you sure you want to delete this user?')) {
        console.log(`Delete user with ID: ${id}`)
        // Example: this.$inertia.delete(`/users/${id}`)
      }
    }
  }
}
</script>
