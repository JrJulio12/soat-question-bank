<template>
    <div class="space-y-4">
        <div class="flex items-center justify-between mb-4">
            <label class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300">
                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                </svg>
                Options <span class="text-red-500 ml-1">*</span>
            </label>
            <button
                v-if="questionType !== 'true_false'"
                type="button"
                @click="addOption"
                class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors flex items-center"
            >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Add Option
            </button>
        </div>

        <div v-if="localOptions.length === 0" class="text-sm text-gray-500 dark:text-gray-400 text-center py-4">
            No options added. Click "Add Option" to get started.
        </div>

        <div v-for="(option, index) in localOptions" :key="index" class="flex items-start gap-3 p-4 border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-[#161615]">
            <div class="flex-1 space-y-3">
                <div class="flex items-center gap-3">
                    <input
                        type="text"
                        v-model="option.text"
                        :disabled="questionType === 'true_false'"
                        :placeholder="`Option ${index + 1}`"
                        required
                        class="flex-1 px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-[#0a0a0a] dark:text-[#EDEDEC] shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all disabled:opacity-50 disabled:cursor-not-allowed"
                        @input="updateOptions"
                    />
                    <button
                        v-if="questionType !== 'true_false' && localOptions.length > 2"
                        type="button"
                        @click="removeOption(index)"
                        class="p-2 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors"
                        title="Remove option"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                    </button>
                </div>
                <div class="flex items-center gap-4">
                    <label class="flex items-center cursor-pointer">
                        <input
                            type="checkbox"
                            v-model="option.is_correct"
                            :disabled="questionType === 'true_false' && !option.is_correct && hasCorrectAnswer"
                            @change="handleCorrectChange(index)"
                            class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600"
                        />
                        <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">Correct Answer</span>
                    </label>
                    <input
                        type="number"
                        v-model.number="option.order"
                        min="1"
                        class="w-20 px-3 py-1 text-sm rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-[#0a0a0a] dark:text-[#EDEDEC] shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all"
                        @input="updateOptions"
                    />
                    <span class="text-xs text-gray-500 dark:text-gray-400">Order</span>
                </div>
            </div>
            <div v-if="questionType !== 'true_false' && localOptions.length > 1" class="flex flex-col gap-1">
                <button
                    type="button"
                    @click="moveOption(index, 'up')"
                    :disabled="index === 0"
                    class="p-1 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded disabled:opacity-50 disabled:cursor-not-allowed"
                    title="Move up"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                    </svg>
                </button>
                <button
                    type="button"
                    @click="moveOption(index, 'down')"
                    :disabled="index === localOptions.length - 1"
                    class="p-1 text-gray-600 dark:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 rounded disabled:opacity-50 disabled:cursor-not-allowed"
                    title="Move down"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                    </svg>
                </button>
            </div>
        </div>

        <div v-if="validationError" class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center">
            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
            </svg>
            {{ validationError }}
        </div>
    </div>
</template>

<script>
import { ref, computed, watch } from 'vue'

export default {
    name: 'QuestionOptions',
    props: {
        questionType: {
            type: String,
            default: ''
        },
        options: {
            type: Array,
            default: () => []
        }
    },
    emits: ['update:options'],
    setup(props, { emit }) {
        const localOptions = ref([])
        const validationError = ref('')

        // Initialize options based on type
        const initializeOptions = () => {
            if (props.questionType === 'true_false') {
                if (props.options && props.options.length === 2) {
                    localOptions.value = [...props.options]
                } else {
                    localOptions.value = [
                        { text: 'True', is_correct: false, order: 1 },
                        { text: 'False', is_correct: false, order: 2 }
                    ]
                }
            } else if (props.options && props.options.length > 0) {
                localOptions.value = [...props.options]
            } else {
                localOptions.value = []
            }
            updateOptions()
        }

        // Watch for question type changes
        watch(() => props.questionType, (newType) => {
            if (newType === 'true_false') {
                if (localOptions.value.length !== 2) {
                    localOptions.value = [
                        { text: 'True', is_correct: false, order: 1 },
                        { text: 'False', is_correct: false, order: 2 }
                    ]
                }
            } else if (newType === 'open') {
                localOptions.value = []
            }
            updateOptions()
        }, { immediate: true })

        // Watch for options prop changes
        watch(() => props.options, (newOptions) => {
            if (newOptions && newOptions.length > 0 && props.questionType !== 'open') {
                localOptions.value = [...newOptions]
            }
        }, { deep: true, immediate: true })

        // Initialize on mount
        initializeOptions()

        const hasCorrectAnswer = computed(() => {
            return localOptions.value.some(opt => opt.is_correct === true)
        })

        const addOption = () => {
            if (props.questionType === 'true_false') return
            const newOrder = localOptions.value.length > 0 ? Math.max(...localOptions.value.map(o => o.order || 0)) + 1 : 1
            localOptions.value.push({ text: '', is_correct: false, order: newOrder })
            updateOptions()
        }

        const removeOption = (index) => {
            if (props.questionType === 'true_false') return
            localOptions.value.splice(index, 1)
            updateOptions()
        }

        const moveOption = (index, direction) => {
            if (props.questionType === 'true_false') return
            const newIndex = direction === 'up' ? index - 1 : index + 1
            if (newIndex >= 0 && newIndex < localOptions.value.length) {
                const [movedOption] = localOptions.value.splice(index, 1)
                localOptions.value.splice(newIndex, 0, movedOption)
                localOptions.value.forEach((opt, i) => {
                    opt.order = i + 1
                })
                updateOptions()
            }
        }

        const handleCorrectChange = (index) => {
            if (props.questionType === 'multiple_choice' || props.questionType === 'true_false') {
                localOptions.value.forEach((opt, idx) => {
                    if (idx !== index) {
                        opt.is_correct = false
                    }
                })
            }
            updateOptions()
            validateOptions()
        }

        const validateOptions = () => {
            validationError.value = ''

            if (localOptions.value.length < 2) {
                validationError.value = 'At least 2 options are required.'
                return false
            }

            if (props.questionType === 'true_false' && localOptions.value.length !== 2) {
                validationError.value = 'True/False questions must have exactly 2 options.'
                return false
            }

            const correctCount = localOptions.value.filter(opt => opt.is_correct).length

            if (props.questionType === 'multiple_choice' || props.questionType === 'true_false') {
                if (correctCount !== 1) {
                    validationError.value = 'Exactly one option must be marked as correct.'
                    return false
                }
            } else if (props.questionType === 'multi_select') {
                if (correctCount < 1) {
                    validationError.value = 'At least one option must be marked as correct.'
                    return false
                }
            }

            const emptyOptions = localOptions.value.filter(opt => !opt.text || opt.text.trim() === '')
            if (emptyOptions.length > 0) {
                validationError.value = 'All options must have text.'
                return false
            }

            return true
        }

        const updateOptions = () => {
            localOptions.value.forEach((opt, idx) => {
                if (!opt.order) {
                    opt.order = idx + 1
                }
            })
            validateOptions()
            emit('update:options', localOptions.value)
        }

        // Watch localOptions for changes
        watch(localOptions, updateOptions, { deep: true })

        // Create a computed ref for questionType to use in template
        const questionType = computed(() => props.questionType)

        return {
            localOptions,
            validationError,
            hasCorrectAnswer,
            addOption,
            removeOption,
            moveOption,
            handleCorrectChange,
            validateOptions,
            updateOptions,
            questionType
        }
    }
}
</script>
