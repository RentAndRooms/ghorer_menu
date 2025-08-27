<template>
    <AdminLayout>
        <div class="bg-white min-h-screen p-8">
            <h2 class="text-center text-xl text-gray-800">Create Local Area</h2>
            <form @submit.prevent="handleSubmit" class="max-w-4xl mx-auto text-gray-800">
                <!-- Username -->
                <div class="mb-6">
                    <label for="name" class="block mb-2 text-sm font-medium text-gray-800">Name</label>
                    <input id="name" v-model="formData.name" type="text"
                        class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        placeholder="Enter name" required />
                    <p v-if="formData.errors.name" class="text-red-500 text-sm mt-1">{{ formData.errors.name }}</p>
                </div>

                <!-- City -->
                <div class="mb-6">
                    <label for="city" class="block mb-2 text-sm font-medium text-gray-800">City</label>
                    <select id="city" v-model="formData.city_id"
                        class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required>
                        <option value="" disabled>Select city</option>
                        <option v-for="(city, index) in cities" :key="index" :value="city.id">
                            {{ city.name }}
                        </option>
                    </select>
                    <p v-if="formData.errors.city" class="text-red-500 text-sm mt-1">{{ formData.errors.city }}</p>
                </div>

                <!-- Area -->
                <div class="mb-6">
                    <label for="area" class="block mb-2 text-sm font-medium text-gray-800">Area</label>
                    <select id="area" v-model="formData.area_id"
                        class="w-full px-4 py-3 bg-gray-100 border border-gray-300 rounded-lg text-gray-800 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                        required>
                        <option value="" disabled>Select area</option>
                        <option v-for="(area, index) in filterAreas" :key="index" :value="area.id">
                            {{ area.name }}
                        </option>
                    </select>
                    <p v-if="formData.errors.area" class="text-red-500 text-sm mt-1">{{ formData.errors.area }}</p>
                </div>

                <!-- Submit Button -->
                <div class="pt-6 border-t border-gray-300">
                    <button type="submit"
                        class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-3 px-8 rounded-lg transition-all duration-200 hover:shadow-lg hover:shadow-blue-500/25 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 focus:ring-offset-slate-700"
                        :disabled="formData.processing">
                        Create
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
        cities: {
            type: Array,
            required: true,
        },
        areas: {
            type: Array,
            required: true,
        },
    },
    data() {
        return {
            formData: useForm({
                name: '',
                city_id: '',
                area_id: '',
            }),
        };
    },
    methods: {
        handleSubmit() {
            this.formData.post(route('admin.locals.store'), {
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
    computed: {
        filterAreas() {
            if (!this.formData.city_id) {
                return [];
            }
            return this.areas.filter(area => area.district_id === this.formData.city_id);
        }
    }
};
</script>