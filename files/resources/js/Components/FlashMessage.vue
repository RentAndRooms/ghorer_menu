<template>
  <div
    v-if="show"
    :class="[
      'rounded-md p-4',
      type === 'success' ? 'bg-green-50 dark:bg-green-900' : 'bg-red-50 dark:bg-red-900'
    ]"
  >
    <div class="flex">
      <div class="flex-shrink-0">
        <i
          v-if="type === 'success'"
          class="fas fa-check-circle h-5 w-5 text-green-400 dark:text-green-300"
          aria-hidden="true"
        ></i>
        <i
          v-else
          class="fas fa-times-circle h-5 w-5 text-red-400 dark:text-red-300"
          aria-hidden="true"
        ></i>
      </div>
      <div class="ml-3">
        <p
          :class="[
            'text-sm font-medium',
            type === 'success' ? 'text-green-800 dark:text-green-200' : 'text-red-800 dark:text-red-200'
          ]"
        >
          {{ message }}
        </p>
      </div>
      <div class="ml-auto pl-3">
        <div class="-mx-1.5 -my-1.5">
          <button
            type="button"
            :class="[
              'inline-flex rounded-md p-1.5',
              type === 'success'
                ? 'bg-green-50 text-green-500 hover:bg-green-100 dark:bg-green-900 dark:text-green-300 dark:hover:bg-green-800'
                : 'bg-red-50 text-red-500 hover:bg-red-100 dark:bg-red-900 dark:text-red-300 dark:hover:bg-red-800'
            ]"
            @click="show = false"
          >
            <span class="sr-only">Dismiss</span>
            <i class="fas fa-times h-5 w-5" aria-hidden="true"></i>
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref } from 'vue'

const props = defineProps({
  message: {
    type: String,
    required: true
  },
  type: {
    type: String,
    default: 'success'
  }
})

const show = ref(true)
</script>

<style scoped>
/* Maintain icon sizing similar to Heroicons */
.fa-check-circle,
.fa-times-circle,
.fa-times {
  font-size: 1.25rem; /* equivalent to h-5 w-5 */
  width: 1.25rem;
  height: 1.25rem;
  line-height: 1.25rem;
  text-align: center;
}
</style>
