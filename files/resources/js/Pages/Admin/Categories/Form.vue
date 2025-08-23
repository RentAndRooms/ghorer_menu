<template>
  <AdminLayout :title="title">
    <template #header>
      <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
        {{ title }}
      </h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
          <h1 class="text-xl font-semibold leading-tight text-center mt-4 text-gray-800 dark:text-gray-200">Create
            Category
          </h1>
          <form @submit.prevent="submit" class="p-6 space-y-6">
            <!-- Name Input -->
            <div>
              <InputLabel for="name" value="Name" />
              <TextInput id="name" type="text" class="mt-1 block w-full" v-model="form.name" required autofocus />
              <InputError :message="form.errors.name" class="mt-2" />
            </div>

            <!-- Description Input -->
            <div>
              <InputLabel for="description" value="Description" />
              <TextArea id="description" class="mt-1 block w-full" v-model="form.description" rows="4" />
              <InputError :message="form.errors.description" class="mt-2" />
            </div>

            <!-- Image Upload -->
            <div>
              <InputLabel for="image" value="Image" />
              <div class="mt-2 flex items-center">
                <img v-if="imagePreview" :src="imagePreview" class="h-32 w-32 object-cover rounded-lg" />
                <img v-else-if="category?.image_path" :src="`/storage/${category.image_path}`"
                  class="h-32 w-32 object-cover rounded-lg" />
                <div v-else class="h-32 w-32 rounded-lg bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                  <i class="fas fa-camera text-gray-400 dark:text-gray-500 text-3xl"></i>
                </div>

                <input type="file" id="image" ref="imageInput" class="hidden" @change="handleImageChange"
                  accept="image/*" />
                <SecondaryButton type="button" class="ml-4" @click="$refs.imageInput.click()">
                  {{ imagePreview || category?.image_path ? 'Change Image' : 'Select Image' }}
                </SecondaryButton>
                <DangerButton v-if="imagePreview || category?.image_path" type="button" class="ml-2"
                  @click="removeImage">
                  Remove
                </DangerButton>
              </div>
              <InputError :message="form.errors.image" class="mt-2" />
            </div>

            <!-- Status Toggle -->
            <div class="flex items-center">
              <InputLabel for="is_active" value="Active Status" class="mr-4" />
              <Toggle v-model="form.is_active" id="is_active" />
            </div>

            <!-- Sort Order -->
            <div>
              <InputLabel for="sort_order" value="Sort Order" />
              <TextInput id="sort_order" type="number" class="mt-1 block w-full" v-model="form.sort_order" min="0" />
              <InputError :message="form.errors.sort_order" class="mt-2" />
            </div>

            <!-- Form Buttons -->
            <div class="flex items-center justify-end space-x-3">
              <Link :href="route('admin.categories.index')"
                class="text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200">
              Cancel
              </Link>
              <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                {{ category ? 'Update Category' : 'Create Category' }}
              </PrimaryButton>
            </div>
          </form>
        </div>
      </div>
    </div>
  </AdminLayout>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link, useForm } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import InputLabel from '@/Components/InputLabel.vue'
import TextInput from '@/Components/TextInput.vue'
import TextArea from '@/Components/TextArea.vue'
import InputError from '@/Components/InputError.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import SecondaryButton from '@/Components/SecondaryButton.vue'
import DangerButton from '@/Components/DangerButton.vue'
import Toggle from '@/Components/Toggle.vue'

const props = defineProps({
  category: {
    type: Object,
    default: null
  }
})

const title = computed(() => props.category ? 'Edit Category' : 'Create Category')
const imageInput = ref(null)
const imagePreview = ref(null)

const form = useForm({
  name: props.category?.name ?? '',
  description: props.category?.description ?? '',
  image: null,
  is_active: props.category?.is_active ?? true,
  sort_order: props.category?.sort_order ?? 0,
  _method: props.category ? 'PUT' : 'POST'
})

const handleImageChange = (e) => {
  const file = e.target.files[0]
  if (file) {
    form.image = file
    const reader = new FileReader()
    reader.onload = (e) => {
      imagePreview.value = e.target.result
    }
    reader.readAsDataURL(file)
  }
}

const removeImage = () => {
  form.image = null
  imagePreview.value = null
  if (imageInput.value) {
    imageInput.value.value = ''
  }
}

const submit = () => {
  if (props.category) {
    form.post(route('admin.categories.update', props.category.id), {
      preserveScroll: true,
      onSuccess: () => {
        imagePreview.value = null
      },
    })
  } else {
    form.post(route('admin.categories.store'), {
      preserveScroll: true,
      onSuccess: () => {
        form.reset()
        imagePreview.value = null
      },
    })
  }
}
</script>
