<template>
    <AdminLayout :title="title">
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">{{ title }}</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <!-- Flash Message if any -->
                <FlashMessage v-if="$page.props.flash.success" :message="$page.props.flash.success" class="mb-4" />

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <form @submit.prevent="submit" class="p-6 space-y-6">
                        <!-- Basic Information Section -->
                        <div class="bg-gray-50 dark:bg-gray-700/50 p-6 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Package Information
                            </h3>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Name -->
                                <div class="col-span-1">
                                    <InputLabel for="name" value="Package Name" required />
                                    <TextInput id="name" v-model="form.name" type="text" class="mt-1 block w-full"
                                        required autofocus />
                                    <InputError :message="form.errors.name" class="mt-2" />
                                </div>

                                <!-- Category -->
                                <div class="col-span-1">
                                    <InputLabel for="category_id" value="Category" required />
                                    <select id="category_id" v-model="form.category_id"
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                        required>
                                        <option value>Select Category</option>
                                        <option v-for="category in categories" :key="category.id" :value="category.id">
                                            {{ category.name }}
                                        </option>
                                    </select>
                                    <InputError :message="form.errors.category_id" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Pricing and Preparation Section -->
                        <div class="bg-gray-50 dark:bg-gray-700/50 p-6 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Pricing and
                                Preparation</h3>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                <div>
                                    <InputLabel for="branche_id" value="Branch" required />
                                    <select id="branche_id" v-model="form.branche_id" @change="handleBranchChange"
                                        class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                        required>
                                        <option value>Select Branch</option>
                                        <option v-for="branch in branches" :key="branch.id" :value="branch.id">{{
                                            branch.name }}
                                        </option>
                                    </select>
                                    <InputError :message="form.errors.branche_id" class="mt-2" />
                                </div>
                                <!-- Base Price -->
                                <div>
                                    <InputLabel for="base_price" value="Price(৳)" required />
                                    <TextInput id="base_price" v-model="form.base_price" type="number" step="0.01"
                                        class="mt-1 block w-full" required />
                                    <InputError :message="form.errors.base_price" class="mt-2" />
                                </div>

                                <!-- Preparation Time -->
                                <div>
                                    <InputLabel for="preparation_time" value="Preparation Time (minutes)" required />
                                    <TextInput id="preparation_time" v-model="form.preparation_time" type="number"
                                        min="1" class="mt-1 block w-full" required />
                                    <InputError :message="form.errors.preparation_time" class="mt-2" />
                                </div>
                            </div>
                            <div class="mt-4" v-if="branch_foods.length > 0">
                                <InputLabel for="selected_foods" value="Select Foods" />
                                <select id="selected_foods" v-model="form.selectedFoods" multiple
                                    class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm h-32">
                                    <option v-for="food in branch_foods" :key="food.id" :value="food.id">
                                        {{ food.name }} (Price: {{ food.base_price }})
                                    </option>
                                </select>
                                <div v-if="form.selectedFoods.length > 0"
                                    class="mt-2 text-sm text-gray-700 dark:text-gray-300">
                                    Selected Foods: {{ selectedFoodsNames }}
                                </div>
                            </div>
                            <div v-else-if="show && branch_foods.length <= 0" class="mt-4 text-red-400">Create Food For
                                The Restaurant First</div>
                            <div v-if="totalPrice > 0" class="mt-4 font-bold">
                                Total Price: {{ totalPrice }}
                            </div>
                        </div>

                        <!-- Image Upload Section -->
                        <div class="bg-gray-50 dark:bg-gray-700/50 p-6 rounded-lg">
                            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Package Image</h3>

                            <div class="flex items-center space-x-6">
                                <div class="flex-shrink-0">
                                    <img v-if="imagePreview" :src="imagePreview"
                                        class="h-32 w-32 object-cover rounded-lg" />
                                    <img v-else-if="food?.image_path" :src="getImageUrl(food.image_path)"
                                        class="h-32 w-32 object-cover rounded-lg" />
                                    <div v-else
                                        class="h-32 w-32 rounded-lg bg-gray-100 dark:bg-gray-700 border-2 border-dashed border-gray-300 dark:border-gray-600 flex items-center justify-center">
                                        <i class="fas fa-camera text-gray-400 dark:text-gray-500 text-3xl"></i>
                                    </div>
                                </div>

                                <div class="flex-1">
                                    <input type="file" ref="imageInput" @change="handleImageChange" accept="image/*"
                                        class="hidden" />
                                    <div class="space-y-2">
                                        <SecondaryButton type="button" @click="$refs.imageInput.click()">{{ imagePreview
                                            || food?.image_path ? 'Change Image' : 'Select Image' }}</SecondaryButton>

                                        <p class="text-sm text-gray-500 dark:text-gray-400">Recommended size: 800x600
                                            pixels</p>

                                        <button v-if="imagePreview || food?.image_path" type="button"
                                            @click="removeImage"
                                            class="text-sm text-red-600 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300">Remove
                                            Image</button>
                                    </div>
                                    <InputError :message="form.errors.image" class="mt-2" />
                                </div>
                            </div>
                        </div>

                        <!-- Availability Status Section -->
                        <div class="bg-gray-50 dark:bg-gray-700/50 p-6 rounded-lg">
                            <div class="flex items-center justify-between">
                                <div>
                                    <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Availability Status
                                    </h3>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">Control whether this food item
                                        is available for ordering</p>
                                </div>
                                <Toggle v-model="form.is_available" :label="'Food Availability'" />
                            </div>
                        </div>

                        <!-- Form Actions -->
                        <div class="flex items-center justify-end space-x-4">
                            <Link :href="route('admin.foods.index')"
                                class="inline-flex items-center px-4 py-2 bg-gray-100 dark:bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-200 dark:hover:bg-gray-700 active:bg-gray-300 dark:active:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                            <i class="fas fa-arrow-left mr-2"></i>
                            Back to List
                            </Link>

                            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                <i class="fas fa-save mr-2"></i>
                                {{ food ? 'Update Package' : 'Create Package' }}
                            </PrimaryButton>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AdminLayout>
</template>

<script setup>
import { ref, computed } from "vue";
import { Link, useForm } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextArea.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import Toggle from "@/Components/Toggle.vue";
import FlashMessage from "@/Components/FlashMessage.vue";
import { v4 as uuidv4 } from "uuid";
import axios from "axios";

const branch_foods = ref([]);
let show = ref(false);

const props = defineProps({
    food: {
        type: Array,
        required: true
    },
    categories: {
        type: Array,
        required: true,
    },
    branches: {
        type: Array,
        required: true,
    },
});

const totalPrice = computed(() => {
    // Ensure branch_foods and selectedFoods are valid arrays
    if (!Array.isArray(branch_foods.value) || !Array.isArray(form.selectedFoods)) {
        return 0;
    }

    return branch_foods.value
        .filter(food =>
            // Ensure food has an id and is selected
            food && typeof food.id !== 'undefined' && form.selectedFoods.includes(food.id)
        )
        .reduce((sum, food) => {
            // Convert base_price to a number, default to 0 if invalid
            const price = parseFloat(food.base_price) || 0;
            return sum + price;
        }, 0)
        .toFixed(2); // Ensure two decimal places for currency
});

const selectedFoodsNames = computed(() => {
    if (!Array.isArray(branch_foods.value) || !Array.isArray(form.selectedFoods)) {
        return '';
    }
    return branch_foods.value
        .filter(food => food && typeof food.id !== 'undefined' && form.selectedFoods.includes(food.id))
        .map(food => food.name)
        .join(', ');
});

const title = computed(() =>
    props.food ? "Edit Food Item" : "Create Food Item"
);
const imageInput = ref(null);
const imagePreview = ref(null);

const form = useForm({
    name: props.food?.name ?? "",
    branche_id: props.food?.branche_id ?? "",
    category_id: props.food?.category_id ?? "",
    description: props.food?.description ?? "",
    base_price: props.food?.base_price ?? "",
    half_price: props.food?.half_price ?? "",
    preparation_time: props.food?.preparation_time ?? 30,
    is_vegetarian: props.food?.is_vegetarian ?? false,
    selectedFoods: props.food?.selectedFoods ?? [],
    is_spicy: props.food?.is_spicy ?? false,
    allergens: props.food?.allergens ?? [],
    image: null,
    is_available: props.food?.is_available ?? true,
    extra_options: (props.food?.extra_options ?? []).map((option) => ({
        ...option,
        tempId: uuidv4(),
    })),
});

const handleBranchChange = async () => {
    try {
        const branchId = form.branche_id;
        const response = await axios.get(`/admin/branch_food/${branchId}`);
        branch_foods.value = response.data;
        show = true;
    } catch (error) {
        console.log('Error fetching branch food :', error);
    }
};

// Extra options handling
const addExtraOption = () => {
    form.extra_options.push({
        tempId: uuidv4(),
        name: "",
        price: "",
        is_available: true,
        sort_order: form.extra_options.length,
    });
};

const removeExtraOption = (index) => {
    form.extra_options.splice(index, 1);
};

const moveOption = (fromIndex, direction) => {
    const toIndex = fromIndex + direction;
    if (toIndex >= 0 && toIndex < form.extra_options.length) {
        const options = [...form.extra_options];
        const temp = options[fromIndex];
        options[fromIndex] = options[toIndex];
        options[toIndex] = temp;
        form.extra_options = options;
    }
};
const drag = ref(false);
const dragOptions = computed(() => ({
    animation: 200,
    disabled: false,
    ghostClass: "ghost",
}));

// Image handling
const handleImageChange = (e) => {
    const file = e.target.files[0];
    if (file) {
        form.image = file;
        const reader = new FileReader();
        reader.onload = (e) => {
            imagePreview.value = e.target.result;
        };
        reader.readAsDataURL(file);
    }
};

const removeImage = () => {
    form.image = null;
    imagePreview.value = null;
    if (imageInput.value) {
        imageInput.value.value = "";
    }
};

const getImageUrl = (imagePath) => {
    const cleanPath = imagePath.startsWith("/") ? imagePath.slice(1) : imagePath;
    return `${window.location.origin}/storage/${cleanPath}`;
};

// Allergen handling
const addAllergen = () => {
    form.allergens.push("");
};

const removeAllergen = (index) => {
    form.allergens.splice(index, 1);
};

// Form submission
const submit = () => {
    if (props.food) {
        form.put(route("admin.foods.update", props.food.id), {
            _method: "PUT",
            onSuccess: () => {
                // Reset form state but keep edited data
                form.clearErrors();
            },
        });
    } else {
        form.post(route("admin.package.create"), {
            onSuccess: () => {
                console.log('Server data:', page.props.flash, page.props)
                form.reset();
                imagePreview.value = null;
            },
        });
    }
};
</script>

<style scoped>
/* Custom styles for drag handle */
.drag-handle {
    cursor: move;
    touch-action: none;
}

/* Price input left padding for $ symbol */
.price-input {
    padding-left: 1.5rem;
}

/* Smooth transitions */
.fade-enter-active,
.fade-leave-active {
    transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
    opacity: 0;
}

/* Custom file input styling */
.file-input-label {
    cursor: pointer;
}

.file-input-label:hover {
    opacity: 0.9;
}

/* Responsive image preview */
@media (max-width: 640px) {
    .image-preview {
        width: 100%;
        height: auto;
    }
}
</style>
