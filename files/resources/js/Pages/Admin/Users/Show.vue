<template>
    <AdminLayout>
        <div class="min-h-screen bg-white py-8">
            <div class="max-w-2xl mx-auto bg-gray-50 rounded-lg shadow p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">User Details</h2>
                <div class="space-y-4">
                    <div>
                        <span class="font-semibold text-gray-700">ID:</span>
                        <span class="ml-2 text-gray-900">{{ user.id }}</span>
                    </div>
                    <div>
                        <span class="font-semibold text-gray-700">Name:</span>
                        <span class="ml-2 text-gray-900">{{ user.name }}</span>
                    </div>
                    <div>
                        <span class="font-semibold text-gray-700">Phone:</span>
                        <span class="ml-2 text-gray-900">{{ user.phone }}</span>
                    </div>
                    <div>
                        <span class="font-semibold text-gray-700">Role:</span>
                        <span class="ml-2 text-gray-900">{{ formatRole(user.role) }}</span>
                    </div>
                    <div v-if="user.rest_id">
                        <span class="font-semibold text-gray-700">Restaurant ID:</span>
                        <span class="ml-2 text-gray-900">{{ user.rest_id }}</span>
                    </div>
                </div>
                <div class="mt-8 flex justify-end gap-4">
                    <Link :href="route('admin.user.edit', user.id)"
                        class="px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold transition">
                    Edit</Link>
                    <Link :href="route('admin.user.index')"
                        class="px-6 py-2 bg-gray-300 hover:bg-gray-400 text-gray-800 rounded-lg font-semibold transition">
                    Back</Link>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script>
import AdminLayout from '@/Layouts/AdminLayout.vue';
import { Link } from '@inertiajs/vue3';
export default {
    components: { AdminLayout, Link },
    props: {
        user: {
            type: Object,
            required: true
        }
    },
    methods: {
        formatRole(role) {
            return role.split('_').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');
        }
    }
};
</script>
