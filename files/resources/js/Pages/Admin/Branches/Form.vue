<template>
  <AdminLayout :title="title">
    <!-- Page Title -->
    <h2 class="text-xl text-center font-semibold leading-tight text-gray-800 dark:text-gray-200">{{ title }}</h2>

    <!-- Form Container -->
    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
          <form @submit.prevent="submit" class="p-6 space-y-6">
            <!-- Basic Information Section -->
            <section class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
              <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Basic Information</h3>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                  <InputLabel for="name" value="Restaurant Name" />
                  <TextInput id="name" v-model="form.name" type="text"
                    class="mt-1 block w-full dark:bg-gray-900 dark:text-gray-300" required />
                  <InputError :message="form.errors.name" class="mt-2" />
                </div>
                <div>
                  <InputLabel for="contact_number" value="Contact Number" />
                  <TextInput id="contact_number" v-model="form.contact_number" type="text"
                    class="mt-1 block w-full dark:bg-gray-900 dark:text-gray-300" required />
                  <InputError :message="form.errors.contact_number" class="mt-2" />
                </div>
                <div class="md:col-span-2">
                  <InputLabel for="address" value="Address" />
                  <TextArea id="address" v-model="form.address"
                    class="mt-1 block w-full dark:bg-gray-900 dark:text-gray-300" rows="2" required />
                  <InputError :message="form.errors.address" class="mt-2" />
                </div>
              </div>
            </section>

            <!-- Location Section -->
            <section class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
              <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                Location & Delivery
                Area
              </h3>
              <div class="grid grid-cols-1 md:grid-cols-1">
                <div>
                  <InputLabel for="map_url" value="Google Map Url" />
                  <TextInput id="map_url" v-model="form.map_url" type="text" step="any"
                    class="mt-1 block w-full dark:bg-gray-900 dark:text-gray-300" required />
                  <InputError :message="form.errors.map_url" class="mt-2" />
                </div>
              </div>
            </section>

            <!-- City and Areas Section -->
            <section class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
              <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">Address</h3>
              <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                <div>
                  <InputLabel for="city_id" value="City" />
                  <select id="city_id" v-model="form.district_id" @change="fetchAreas"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:text-gray-300"
                    required>
                    <option value>Select a city</option>
                    <option v-for="city in cities" :key="city.id" :value="city.id">{{ city.name }}</option>
                  </select>
                  <InputError :message="form.errors.district_id" class="mt-2" />
                </div>
                <div>
                  <InputLabel for="thana_id" value="Area" />
                  <select id="thana_id" v-model="form.thana_id"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:text-gray-300"
                    required>
                    <option value>Select an Area</option>
                    <option v-for="area in areas" :key="area.id" :value="area.id">{{ area.name }}</option>
                  </select>
                  <InputError :message="form.errors.thana_id" class="mt-2" />
                </div>
                <div>
                  <InputLabel for="local_id" value="Local" />
                  <select id="local_id" v-model="form.local_id"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm dark:bg-gray-900 dark:text-gray-300">
                    <option value>Select a Local Area</option>
                    <option v-for="local in filterLocal" :key="local.id" :value="local.id">
                      {{
                        local.name }}
                    </option>
                  </select>
                  <InputError :message="form.errors.local_id" class="mt-2" />
                </div>
              </div>
            </section>

            <!-- Opening Hours & Meal Times Section -->
            <section class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
              <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-4">
                Opening Hours & Meal
                Times
              </h3>
              <div class="space-y-6">
                <div v-for="(hours, index) in form.opening_hours" :key="index"
                  class="space-y-4 border p-4 rounded-lg dark:border-gray-600">
                  <div class="flex items-start space-x-4">
                    <div class="w-32">
                      <InputLabel :for="`day-${index}`" value="Day" />
                      <select :id="`day-${index}`" v-model="hours.day"
                        class="mt-1 block w-full dark:bg-gray-900 dark:text-gray-300" required>
                        <option v-for="day in weekDays" :key="day" :value="day">{{ day }}</option>
                      </select>
                    </div>
                    <div>
                      <InputLabel :for="`open-${index}`" value="Opening Time" />
                      <TextInput :id="`open-${index}`" v-model="hours.open" type="time"
                        class="mt-1 block w-full dark:bg-gray-900 dark:text-gray-300" required />
                    </div>
                    <div>
                      <InputLabel :for="`close-${index}`" value="Closing Time" />
                      <TextInput :id="`close-${index}`" v-model="hours.close" type="time"
                        class="mt-1 block w-full dark:bg-gray-900 dark:text-gray-300" required />
                    </div>
                    <button type="button" @click="removeOpeningHours(index)"
                      class="mt-7 text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-red-300">
                      <i class="fas fa-trash"></i>
                    </button>
                  </div>

                  <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                      <InputLabel :for="`breakfast-${index}`" value="Breakfast Time" />
                      <div class="flex gap-2">
                        <TextInput :id="`breakfast-start-${index}`" v-model="hours.breakfast.start" type="time"
                          class="mt-1 w-1/2 dark:bg-gray-900 dark:text-gray-300" />
                        <TextInput :id="`breakfast-end-${index}`" v-model="hours.breakfast.end" type="time"
                          class="mt-1 w-1/2 dark:bg-gray-900 dark:text-gray-300" />
                      </div>
                    </div>

                    <div>
                      <InputLabel :for="`lunch-${index}`" value="Lunch Time" />
                      <div class="flex gap-2">
                        <TextInput :id="`lunch-start-${index}`" v-model="hours.lunch.start" type="time"
                          class="mt-1 w-1/2 dark:bg-gray-900 dark:text-gray-300" />
                        <TextInput :id="`lunch-end-${index}`" v-model="hours.lunch.end" type="time"
                          class="mt-1 w-1/2 dark:bg-gray-900 dark:text-gray-300" />
                      </div>
                    </div>

                    <div>
                      <InputLabel :for="`dinner-${index}`" value="Dinner Time" />
                      <div class="flex gap-2">
                        <TextInput :id="`dinner-start-${index}`" v-model="hours.dinner.start" type="time"
                          class="mt-1 w-1/2 dark:bg-gray-900 dark:text-gray-300" />
                        <TextInput :id="`dinner-end-${index}`" v-model="hours.dinner.end" type="time"
                          class="mt-1 w-1/2 dark:bg-gray-900 dark:text-gray-300" />
                      </div>
                    </div>
                  </div>
                </div>

                <button type="button" @click="addOpeningHours"
                  class="mt-2 inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 transition ease-in-out duration-150">
                  <i class="fas fa-plus mr-2"></i> Add Day
                </button>
                <InputError :message="form.errors['opening_hours']" class="mt-2" />
              </div>
            </section>
            <div>
              <InputLabel for="image" value="Image" />
              <div class="mt-2 flex items-center">
                <img v-if="imagePreview" :src="imagePreview" class="h-32 w-32 object-cover rounded-lg" />
                <img v-else-if="branch?.image_path" :src="`/storage/${branch.image_path}`"
                  class="h-32 w-32 object-cover rounded-lg" />
                <div v-else class="h-32 w-32 rounded-lg bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                  <i class="fas fa-camera text-gray-400 dark:text-gray-500 text-3xl"></i>
                </div>

                <input type="file" id="image" ref="imageInput" class="hidden" @change="handleImageChange"
                  accept="image/*" />
                <SecondaryButton type="button" class="ml-4" @click="$refs.imageInput.click()">{{ imagePreview ||
                  branch?.image_path ? 'Change Image' : 'Select Image' }}</SecondaryButton>
                <DangerButton v-if="imagePreview || branch?.image_path" type="button" class="ml-2" @click="removeImage">
                  Remove</DangerButton>
              </div>
              <InputError :message="form.errors.image" class="mt-2" />
            </div>

            <!-- Status Toggle Section -->
            <section class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg">
              <div class="flex items-center justify-between">
                <div>
                  <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">Branch Status</h3>
                  <p class="text-sm text-gray-500 dark:text-gray-400">Enable or disable this branch</p>
                </div>
                <Toggle v-model="form.is_active" class="ml-4" />
              </div>
            </section>

            <!-- Form Actions -->
            <div class="flex items-center justify-end space-x-3">
              <Link :href="route('admin.branches.index')"
                class="inline-flex items-center px-4 py-2 bg-gray-200 dark:bg-gray-700 border border-transparent rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest hover:bg-gray-300 dark:hover:bg-gray-600 focus:bg-gray-300 dark:focus:bg-gray-600 active:bg-gray-300 dark:active:bg-gray-600">
              Cancel</Link>
              <PrimaryButton :disabled="form.processing">{{ branch ? 'Update Branch' : 'Create Branch' }}
              </PrimaryButton>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { computed, ref, watch, onMounted } from "vue";
import { Link, useForm } from "@inertiajs/vue3";
import AdminLayout from "@/Layouts/AdminLayout.vue";
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import TextArea from "@/Components/TextArea.vue";
import InputError from "@/Components/InputError.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import DangerButton from "@/Components/DangerButton.vue";
import Toggle from "@/Components/Toggle.vue";
import axios from "axios";

// Props definition
const imageInput = ref(null);
const imagePreview = ref(null);
const props = defineProps({
  branch: {
    type: Object,
    default: null,
  },
  cities: {
    type: Array,
    default: () => [],
  },
  locals: {
    type: Array,
    default: () => [],
  },
});

// Reactive reference for areas
const areas = ref([]);

// Computed properties
const title = computed(() =>
  props.branch ? "Edit Restaurant" : "Create Restaurant"
);

// Constants
const weekDays = [
  "Monday",
  "Tuesday",
  "Wednesday",
  "Thursday",
  "Friday",
  "Saturday",
  "Sunday",
];

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

// Helper function to normalize opening hours structure
const normalizeOpeningHours = (openingHours) => {
  if (!openingHours || !Array.isArray(openingHours)) {
    return [
      {
        day: "Monday",
        open: "09:00",
        close: "22:00",
        breakfast: { start: "08:00", end: "10:00" },
        lunch: { start: "12:00", end: "15:00" },
        dinner: { start: "18:00", end: "22:00" },
      },
    ];
  }

  return openingHours.map((hours) => ({
    day: hours.day || "Monday",
    open: hours.open || "09:00",
    close: hours.close || "22:00",
    breakfast: hours.breakfast || { start: "08:00", end: "10:00" },
    lunch: hours.lunch || { start: "12:00", end: "15:00" },
    dinner: hours.dinner || { start: "18:00", end: "22:00" },
  }));
};

// Form initialization
const form = useForm({
  name: props.branch?.name ?? "",
  address: props.branch?.address ?? "",
  latitude: props.branch?.latitude ?? "",
  map_url: props.branch?.map_url ?? "",
  contact_number: props.branch?.contact_number ?? "",
  district_id: props.branch?.district_id ?? "",
  thana_id: props.branch?.thana_id ?? "",
  local_id: props.branch?.local_id ?? "",
  image: props.branch?.image ?? "",
  opening_hours: normalizeOpeningHours(props.branch?.opening_hours),
  delivery_radius: props.branch?.delivery_radius ?? 5,
  is_active: props.branch?.is_active ?? true,
});

// Add opening hours function
const addOpeningHours = () => {
  const availableDay =
    weekDays.find((day) => !form.opening_hours.some((h) => h.day === day)) ||
    weekDays[0];
  form.opening_hours.push({
    day: availableDay,
    open: "09:00",
    close: "22:00",
    breakfast: { start: "08:00", end: "10:00" },
    lunch: { start: "12:00", end: "15:00" },
    dinner: { start: "18:00", end: "22:00" },
  });
};

const filterLocal = computed(() => {
  if (!form.thana_id) {
    return [];
  }
  return props.locals.filter((local) => local.area_id == form.thana_id);
});

// Remove opening hours function
const removeOpeningHours = (index) => {
  if (form.opening_hours.length > 1) {
    form.opening_hours.splice(index, 1);
  }
};

// Fetch areas when district_id changes
const fetchAreas = async () => {
  if (!form.district_id) {
    areas.value = [];
    form.thana_id = "";
    return;
  }

  try {
    const response = await axios.get(`/admin/area/${form.district_id}`);
    areas.value = response.data;

    // Reset thana_id if the current thana_id is not in the new areas list
    if (!areas.value.some((area) => area.id === form.thana_id)) {
      form.thana_id = "";
    }
  } catch (error) {
    console.error("Error fetching areas:", error);
    areas.value = [];
  }
};

// Watch for changes in district_id to fetch areas
watch(
  () => form.district_id,
  () => {
    fetchAreas();
  }
);

// Fetch areas on component mount if district_id exists (for edit mode)
onMounted(() => {
  if (form.district_id) {
    fetchAreas();
  }
});

// Form submission
const submit = () => {
  if (props.branch) {
    form.put(route("admin.branches.update", props.branch.id));
  } else {
    form.post(route("admin.branches.store"));
  }
};
</script>
<style scoped>
/* Custom range slider styling */
input[type="range"]::-webkit-slider-thumb {
  @apply w-4 h-4 bg-indigo-600 rounded-full appearance-none cursor-pointer;
}

input[type="range"]::-moz-range-thumb {
  @apply w-4 h-4 bg-indigo-600 rounded-full cursor-pointer;
}

.dark input[type="range"]::-webkit-slider-thumb {
  @apply bg-indigo-500;
}

.dark input[type="range"]::-moz-range-thumb {
  @apply bg-indigo-500;
}

/* Hover states */
input[type="range"]::-webkit-slider-thumb:hover {
  @apply bg-indigo-700;
}

input[type="range"]::-moz-range-thumb:hover {
  @apply bg-indigo-700;
}

.dark input[type="range"]::-webkit-slider-thumb:hover {
  @apply bg-indigo-400;
}

.dark input[type="range"]::-moz-range-thumb:hover {
  @apply bg-indigo-400;
}

/* Custom range slider styling */
input[type="range"]::-webkit-slider-thumb {
  @apply w-4 h-4 bg-indigo-600 dark:bg-indigo-500 rounded-full cursor-pointer;
}

input[type="range"]::-moz-range-thumb {
  @apply w-4 h-4 bg-indigo-600 dark:bg-indigo-500 rounded-full cursor-pointer;
}

input[type="range"]::-webkit-slider-thumb:hover {
  @apply bg-indigo-700 dark:bg-indigo-400;
}
</style>
