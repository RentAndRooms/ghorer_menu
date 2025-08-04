// resources/js/Components/SimplePagination.vue

<template>
  <div v-if="meta && meta.total > 0" class="flex items-center justify-between px-4 py-3 sm:px-6">
    <!-- Mobile view -->
    <div class="flex flex-1 justify-between sm:hidden">
      <Link
        v-if="meta.prev_page_url"
        :href="meta.prev_page_url"
        class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700"
      >
        Previous
      </Link>
      <span
        v-else
        class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md cursor-not-allowed"
      >
        Previous
      </span>

      <Link
        v-if="meta.next_page_url"
        :href="meta.next_page_url"
        class="relative ml-3 inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-200 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-md hover:bg-gray-50 dark:hover:bg-gray-700"
      >
        Next
      </Link>
      <span
        v-else
        class="relative ml-3 inline-flex items-center px-4 py-2 text-sm font-medium text-gray-500 dark:text-gray-400 bg-gray-100 dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded-md cursor-not-allowed"
      >
        Next
      </span>
    </div>

    <!-- Desktop view -->
    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
      <!-- Results info -->
      <div>
        <p class="text-sm text-gray-700 dark:text-gray-300">
          Showing
          <span class="font-medium">{{ meta.from || 0 }}</span>
          to
          <span class="font-medium">{{ meta.to || 0 }}</span>
          of
          <span class="font-medium">{{ meta.total }}</span>
          results
        </p>
      </div>

      <!-- Page numbers -->
      <div v-if="links && links.length > 3">
        <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
          <!-- Previous page -->
          <Link
            v-if="meta.prev_page_url"
            :href="meta.prev_page_url"
            class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 dark:text-gray-500 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 focus:z-20 focus:outline-offset-0"
          >
            <span class="sr-only">Previous</span>
            <i class="fas fa-chevron-left h-5 w-5"></i>
          </Link>

          <!-- Page numbers -->
          <template v-for="(link, i) in links" :key="i">
            <Link
              v-if="link.url && !link.label.includes('Previous') && !link.label.includes('Next')"
              :href="link.url"
              :class="[
                'relative inline-flex items-center px-4 py-2 text-sm font-semibold',
                link.active
                  ? 'z-10 bg-indigo-600 dark:bg-indigo-500 text-white focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600'
                  : 'text-gray-900 dark:text-gray-100 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 focus:z-20 focus:outline-offset-0',
              ]"
            >
              {{ link.label }}
            </Link>
          </template>

          <!-- Next page -->
          <Link
            v-if="meta.next_page_url"
            :href="meta.next_page_url"
            class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 dark:text-gray-500 ring-1 ring-inset ring-gray-300 dark:ring-gray-600 hover:bg-gray-50 dark:hover:bg-gray-700 focus:z-20 focus:outline-offset-0"
          >
            <span class="sr-only">Next</span>
            <i class="fas fa-chevron-right h-5 w-5"></i>
          </Link>
        </nav>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  links: {
    type: Array,
    default: () => []
  },
  meta: {
    type: Object,
    default: () => ({
      current_page: 1,
      from: 1,
      last_page: 1,
      path: '',
      per_page: 15,
      to: 1,
      total: 0,
      prev_page_url: null,
      next_page_url: null
    })
  }
})
</script>

<style scoped>
.fa-chevron-left,
.fa-chevron-right {
  font-size: 0.875rem;
}
</style>
