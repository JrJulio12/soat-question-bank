<template>
    <div class="p-4 bg-blue-100 dark:bg-blue-900/20 border border-blue-300 dark:border-blue-700 rounded-lg">
        <h3 class="text-lg font-semibold text-blue-800 dark:text-blue-200 mb-2">
            Example Vue 2 Component
        </h3>
        <p class="text-blue-700 dark:text-blue-300">
            This is a simple Vue 2 component working!
        </p>
        <p class="text-sm text-blue-600 dark:text-blue-400 mt-2">
            Counter: {{ count }}
        </p>
        <button 
            @click="increment"
            class="mt-3 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors"
        >
            Click me! ({{ count }})
        </button>
    </div>
</template>

<script>
    export default {
        name: 'ExampleComponent',
        data() {
            return {
                count: 0
            }
        },
        methods: {
            increment() {
                this.count++;
            }
        }
    }
</script>

