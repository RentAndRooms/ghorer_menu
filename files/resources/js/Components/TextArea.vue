<template>
  <textarea
    :value="modelValue"
    @input="$emit('update:modelValue', $event.target.value)"
    ref="input"
    class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm w-full"
    :class="{ 'border-red-500 dark:border-red-500': error }"
    v-bind="$attrs"
  ></textarea>
  <p v-if="error" class="text-red-500 dark:text-red-400 text-sm mt-1">{{ error }}</p>
</template>

<script setup>
import { onMounted, ref } from 'vue'

const input = ref(null)

const props = defineProps({
  modelValue: {
    type: [String, Number],
    default: ''
  },
  error: {
    type: String,
    default: ''
  }
})

defineEmits(['update:modelValue'])

onMounted(() => {
  if (input.value.hasAttribute('autofocus')) {
    input.value.focus()
  }
})

// Make the input methods available to the parent component
defineExpose({ focus: () => input.value.focus() })
</script>

<style scoped>
textarea {
  min-height: 80px;
  resize: vertical;
}

textarea:disabled {
  opacity: 0.5;
  cursor: not-allowed;
}

/* For dark mode placeholders */
textarea::placeholder {
  color: #9ca3af;
}

.dark textarea::placeholder {
  color: #6b7280;
}

/* Custom scrollbar for modern browsers */
textarea::-webkit-scrollbar {
  width: 8px;
}

textarea::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 4px;
}

.dark textarea::-webkit-scrollbar-track {
  background: #374151;
}

textarea::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 4px;
}

.dark textarea::-webkit-scrollbar-thumb {
  background: #4b5563;
}

textarea::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}

.dark textarea::-webkit-scrollbar-thumb:hover {
  background: #6b7280;
}
</style>
