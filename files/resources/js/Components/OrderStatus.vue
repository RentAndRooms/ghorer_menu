<template>
    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium" :class="statusClasses">
      {{ formatStatus(status) }}
    </span>
  </template>

  <script setup>
  import { computed } from 'vue'

  const props = defineProps({
    status: {
      type: String,
      required: true
    }
  })

  const statusClasses = computed(() => {
    const classes = {
      pending: 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/50 dark:text-yellow-300',
      confirmed: 'bg-blue-100 text-blue-800 dark:bg-blue-900/50 dark:text-blue-300',
      preparing: 'bg-purple-100 text-purple-800 dark:bg-purple-900/50 dark:text-purple-300',
      ready_for_delivery: 'bg-indigo-100 text-indigo-800 dark:bg-indigo-900/50 dark:text-indigo-300',
      out_for_delivery: 'bg-cyan-100 text-cyan-800 dark:bg-cyan-900/50 dark:text-cyan-300',
      delivered: 'bg-green-100 text-green-800 dark:bg-green-900/50 dark:text-green-300',
      cancelled: 'bg-red-100 text-red-800 dark:bg-red-900/50 dark:text-red-300'
    }

    return classes[props.status] || 'bg-gray-100 text-gray-800 dark:bg-gray-900/50 dark:text-gray-300'
  })

  const formatStatus = (status) => {
    return status.split('_').map(word =>
      word.charAt(0).toUpperCase() + word.slice(1)
    ).join(' ')
  }
  </script>
