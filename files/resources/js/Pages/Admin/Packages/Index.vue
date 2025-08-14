<template>
    <AdminLayout title="packages">
        <div class="flex justify-between items-center">
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">Package Management</h2>
            <Link v-if="props.auth.user.role == 'super_admin'" :href="route('admin.package.create')"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md shadow-sm transition dark:bg-indigo-500 dark:hover:bg-indigo-600">
            <i class="fas fa-plus mr-2"></i>
            Add Package
            </Link>
        </div>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <!-- Flash Messages -->
                        <FlashMessage v-if="flash.success" :message="flash.success" class="mb-4" />

                        <!-- packages Table -->
                        <div v-if="true" class="overflow-x-auto">
                            <table class="w-full divide-y divide-gray-200 dark:divide-gray-700">
                                <thead class="bg-gray-50 dark:bg-gray-700/50">
                                    <tr>
                                        <th scope="col"
                                            class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Name</th>
                                        <th scope="col"
                                            class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                            Items</th>
                                        <th scope="col"
                                            class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-32">
                                            Branch</th>
                                        <th scope="col"
                                            class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-32">
                                            Category</th>
                                        <th scope="col"
                                            class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-32">
                                            Image</th>
                                        <th scope="col"
                                            class="px-6 py-4 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider w-40">
                                            Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                    <tr v-for="pack in packages.data" :key="pack.id"
                                        class="group transition-colors duration-150 hover:bg-gray-50/90 dark:hover:bg-gray-700/50">
                                        <td class="px-6 py-4">
                                            <div class="text-sm font-medium text-gray-900 dark:text-white">{{
                                                pack.name }}</div>
                                            <div class="text-sm text-gray-500 dark:text-gray-400">{{
                                                pack.contact_number }}</div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-wrap gap-2">
                                                <span v-for="item in pack.foods" :key="item.id"
                                                    class="bg-gray-100 text-green-600 px-2 py-1 rounded">
                                                    {{ item.name }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div
                                                class="inline-flex items-center px-2.5 py-0.5 rounded-full text-sm bg-gray-100 dark:bg-gray-700 text-gray-800 dark:text-gray-200">
                                                {{ pack.branch.name }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <p>{{ pack.category.name }}</p>
                                        </td>
                                        <td class="px-6 py-4">
                                            <img src="" alt="">
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center space-x-4">
                                                <Link :href="route('admin.package.edit', pack.id)"
                                                    class="inline-flex items-center px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 active:bg-indigo-200 dark:bg-indigo-900/50 dark:hover:bg-indigo-800/50 text-indigo-600 dark:text-indigo-300 rounded-md transition-colors duration-200">
                                                <i class="fas fa-edit text-xs mr-1.5"></i>
                                                <span>Edit</span>
                                                </Link>
                                                <button @click="confirmDelete(pack)"
                                                    class="inline-flex items-center px-3 py-1.5 bg-red-50 hover:bg-red-100 active:bg-red-200 dark:bg-red-900/50 dark:hover:bg-red-800/50 text-red-600 dark:text-red-300 rounded-md transition-colors duration-200">
                                                    <i class="fas fa-trash text-xs mr-1.5"></i>
                                                    <span>Delete</span>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <!-- Empty State -->
                        <div v-else class="text-center py-12">
                            <i class="fas fa-store text-gray-400 text-5xl mb-4"></i>
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">No Package Found</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by creating a new
                                package.</p>
                            <div class="mt-6">
                                <Link :href="route('admin.package.create')"
                                    class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-md">
                                <i class="fas fa-plus mr-2"></i>
                                Add Package
                                </Link>
                            </div>
                        </div>

                        <!-- Pagination -->
                        <!-- <div v-if="packages.data.length > 0" class="mt-4">
                            <SimplePagination :links="packages.meta.links" :meta="packages.meta" />
                        </div> -->
                    </div>
                </div>
            </div>
        </div>

        <!-- Delete Confirmation Modal -->
        <Modal :show="showDeleteModal" @close="showDeleteModal = false">
            <div class="p-6">
                <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">Delete Restuarent</h2>
                <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">Are you sure you want to delete this
                    restuarent? This
                    action cannot be undone.</p>
                <div class="mt-6 flex justify-end space-x-3">
                    <SecondaryButton @click="showDeleteModal = false">Cancel</SecondaryButton>
                    <DangerButton class="ml-3" :class="{ 'opacity-25': form.processing }" :disabled="form.processing"
                        @click="deletePackage">Delete Package</DangerButton>
                </div>
            </div>
        </Modal>
    </AdminLayout>
</template>

<script setup>
import { ref } from "vue";
import { Link, useForm } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import SimplePagination from "@/Components/SimplePagination.vue";
import FlashMessage from "@/Components/FlashMessage.vue";
import Modal from "@/Components/Modal.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";

const props = defineProps({
    packages: {
        type: Object,
        required: true,
        default: () => ({
            data: [],
            meta: {
                current_page: 1,
                from: 1,
                last_page: 1,
                links: [],
                path: "",
                per_page: 10,
                to: 1,
                total: 0,
            },
        }),
    },
    flash: {
        type: Object,
        default: () => ({
            success: null,
            error: null,
        }),
    },
    auth: {
        type: Object,
        default: () => ({
            user: null,
        }),
    },
});

const showDeleteModal = ref(false);
const packageToDelete = ref(null);
const form = useForm({});

const confirmDelete = (pack) => {
    packageToDelete.value = pack;
    showDeleteModal.value = true;
};

const deletepackage = () => {
    if (packageToDelete.value) {
        form.delete(route("admin.package.destroy", packageToDelete.value.id), {
            preserveScroll: true,
            onSuccess: () => {
                showDeleteModal.value = false;
                packageToDelete.value = null;
            },
        });
    }
};
</script>
