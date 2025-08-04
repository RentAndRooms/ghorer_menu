
<template>
  <div v-if="links.length > 3" class="flex flex-wrap -mb-1">
    <template v-for="(link, key) in links" :key="key">
      <div
        v-if="link.url === null"
        class="mr-1 mb-1 px-4 py-2 text-sm leading-4 text-gray-500 dark:text-gray-400 border border-gray-300 dark:border-gray-700 rounded-md cursor-not-allowed"
        v-html="link.label"
      />

      <Link
        v-else
        :href="link.url"
        class="mr-1 mb-1 px-4 py-2 text-sm leading-4 border rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800"
        :class="{
          'bg-white dark:bg-gray-800 text-gray-700 dark:text-gray-300 border-gray-300 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700': !link.active,
          'bg-indigo-600 dark:bg-indigo-500 text-white border-indigo-600 dark:border-indigo-500': link.active
        }"
        preserve-scroll
        v-html="link.label"
      />
    </template>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3'

defineProps({
  links: {
    type: Array,
    required: true,
  },
})
</script>

<style scoped>
/* Style for the rendered HTML entities in pagination labels */
:deep(.px-4) {
  padding-left: 1rem;
  padding-right: 1rem;
}

:deep(svg) {
  height: 1rem;
  width: 1rem;
}

/* Fix for HTML entities spacing */
:deep(.px-4 span) {
  display: inline-flex;
  align-items: center;
}
</style>