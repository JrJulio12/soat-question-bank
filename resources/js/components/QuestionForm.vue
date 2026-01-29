<template>
    <form @submit.prevent="submitForm" class="space-y-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <!-- Stage Field -->
            <div>
                <label for="stage" class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Stage <span class="text-red-500 ml-1">*</span>
                </label>
                <select 
                    v-model="form.stage" 
                    id="stage" 
                    required
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-[#0a0a0a] dark:text-[#EDEDEC] shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all"
                    :class="errors.stage ? 'border-red-500 focus:ring-red-500/20' : ''"
                >
                    <option value="">Select a stage</option>
                    <option value="EF">EF</option>
                    <option value="EM">EM</option>
                </select>
                <p v-if="errors.stage" class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center animate-pulse">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    {{ errors.stage[0] }}
                </p>
            </div>

            <!-- Type Field -->
            <div>
                <label for="type" class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                    </svg>
                    Type <span class="text-red-500 ml-1">*</span>
                </label>
                <select 
                    v-model="form.type" 
                    id="type" 
                    required
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-[#0a0a0a] dark:text-[#EDEDEC] shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all"
                    :class="errors.type ? 'border-red-500 focus:ring-red-500/20' : ''"
                >
                    <option value="">Select a type</option>
                    <option value="multiple_choice">Multiple Choice</option>
                    <option value="multi_select">Multi Select</option>
                    <option value="true_false">True/False</option>
                    <option value="open">Open</option>
                </select>
                <p v-if="errors.type" class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center animate-pulse">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    {{ errors.type[0] }}
                </p>
            </div>

            <!-- Status Field -->
            <div>
                <label for="status" class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                    Status <span class="text-red-500 ml-1">*</span>
                </label>
                <select 
                    v-model="form.status" 
                    id="status" 
                    required
                    class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-[#0a0a0a] dark:text-[#EDEDEC] shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all"
                    :class="errors.status ? 'border-red-500 focus:ring-red-500/20' : ''"
                >
                    <option value="">Select a status</option>
                    <option value="draft">Draft</option>
                    <option value="published">Published</option>
                </select>
                <p v-if="errors.status" class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center animate-pulse">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    {{ errors.status[0] }}
                </p>
            </div>
        </div>
        
        <!-- Stem Field -->
        <div>
            <label for="stem" class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Stem <span class="text-red-500 ml-1">*</span>
            </label>
            <textarea 
                v-model="form.stem" 
                id="stem" 
                rows="5" 
                required
                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-[#0a0a0a] dark:text-[#EDEDEC] shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all resize-none"
                :class="errors.stem ? 'border-red-500 focus:ring-red-500/20' : ''"
            ></textarea>
            <p v-if="errors.stem" class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center animate-pulse">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                {{ errors.stem[0] }}
            </p>
        </div>

        <!-- Answer Text Field (only for "open" type) -->
        <div v-if="showAnswerText">
            <label for="answer_text" class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Answer Text
            </label>
            <textarea 
                v-model="form.answer_text" 
                id="answer_text" 
                rows="4"
                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-[#0a0a0a] dark:text-[#EDEDEC] shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all resize-none"
                :class="errors.answer_text ? 'border-red-500 focus:ring-red-500/20' : ''"
            ></textarea>
            <p v-if="errors.answer_text" class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center animate-pulse">
                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
                {{ errors.answer_text[0] }}
            </p>
        </div>

        <!-- Options Field (for multiple_choice, multi_select, true_false) - Under Type Row -->
        <div v-if="showOptions" class="mt-6">
            <div class="space-y-3">
                <div class="flex items-center justify-between mb-4">
                    <label class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300">
                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                        </svg>
                        Options <span class="text-red-500 ml-1">*</span>
                    </label>
                    <button
                        v-if="form.type !== 'true_false'"
                        type="button"
                        @click="addOption"
                        class="px-4 py-2 text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 rounded-lg transition-colors flex items-center shadow-sm hover:shadow-md"
                    >
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Add Option
                    </button>
                </div>

                <div v-if="form.options.length === 0" class="text-sm text-gray-500 dark:text-gray-400 text-center py-6 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg">
                    No options added. Click "Add Option" to get started.
                </div>

                <div 
                    v-for="(option, index) in form.options" 
                    :key="`option-${index}`" 
                    :draggable="form.type !== 'true_false'"
                    @dragstart="dragStart(index, $event)"
                    @dragover.prevent="dragOver($event)"
                    @drop="drop(index, $event)"
                    @dragenter.prevent
                    class="group relative flex items-start gap-3 p-4 border-2 border-gray-200 dark:border-gray-700 rounded-xl bg-white dark:bg-[#161615] hover:border-blue-400 dark:hover:border-blue-600 transition-all cursor-move shadow-sm hover:shadow-md"
                    :class="draggedIndex === index ? 'opacity-50 border-blue-500' : ''"
                >
                    <!-- Drag Handle -->
                    <div v-if="form.type !== 'true_false' && form.options.length > 1" class="flex flex-col items-center justify-center pt-1 text-gray-400 dark:text-gray-500 group-hover:text-blue-500 transition-colors">
                        <svg class="w-5 h-5 cursor-grab active:cursor-grabbing" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"></path>
                        </svg>
                    </div>
                    
                    <div class="flex-1 space-y-3">
                        <div class="flex items-center gap-3">
                            <input
                                type="text"
                                v-model="option.text"
                                :disabled="form.type === 'true_false'"
                                :placeholder="`Option ${index + 1}`"
                                required
                                class="flex-1 px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-[#0a0a0a] dark:text-[#EDEDEC] shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all disabled:opacity-50 disabled:cursor-not-allowed font-medium"
                                @input="updateOptions"
                            />
                            <button
                                v-if="form.type !== 'true_false' && form.options.length > 2"
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
                            <label class="flex items-center cursor-pointer group/checkbox">
                                <input
                                    type="checkbox"
                                    v-model="option.is_correct"
                                    :disabled="form.type === 'true_false' && !option.is_correct && hasCorrectAnswer"
                                    @change="handleCorrectChange(index)"
                                    class="w-5 h-5 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-800 focus:ring-2 dark:bg-gray-700 dark:border-gray-600 cursor-pointer"
                                />
                                <span class="ml-2 text-sm font-medium text-gray-700 dark:text-gray-300 group-hover/checkbox:text-blue-600 dark:group-hover/checkbox:text-blue-400 transition-colors">
                                    Correct Answer
                                </span>
                            </label>
                            <div class="flex items-center gap-2">
                                <span class="text-xs text-gray-500 dark:text-gray-400 font-medium">Order: {{ option.order }}</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Correct Badge -->
                    <div v-if="option.is_correct" class="absolute top-2 right-2">
                        <span class="px-2 py-1 text-xs font-semibold text-green-700 dark:text-green-300 bg-green-100 dark:bg-green-900/30 rounded-full">
                            ✓ Correct
                        </span>
                    </div>
                </div>

                <div v-if="optionsValidationError" class="mt-2 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg text-red-700 dark:text-red-300 text-sm flex items-center">
                    <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    {{ optionsValidationError }}
                </div>
                <p v-if="errors.options" class="mt-2 p-3 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg text-red-700 dark:text-red-300 text-sm flex items-center animate-pulse">
                    <svg class="w-4 h-4 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                    </svg>
                    {{ errors.options[0] }}
                </p>
            </div>
        </div>

        <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-6">Relationships</h3>
            
            <div :class="showSubjects ? 'grid grid-cols-1 lg:grid-cols-2 gap-6' : 'grid grid-cols-1 gap-6'">
                <!-- BNCCs Cascading Selection -->
                <div class="bg-gray-50 dark:bg-[#0a0a0a] rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <label class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300">
                            <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            BNCCs
                        </label>
                        <button
                            v-if="selectedBnccDisciplines.length > 0 || selectedBnccUnits.length > 0 || selectedBnccKnowledges.length > 0"
                            type="button"
                            @click="selectedBnccDisciplines = []; selectedBnccUnits = []; selectedBnccKnowledges = []; form.bnccs = []"
                            class="text-xs text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors"
                        >
                            Clear filters
                        </button>
                    </div>
                    
                    <div class="space-y-6">
                        <!-- Discipline Level - Card-based selection -->
                        <div>
                            <div class="flex items-center justify-between mb-3">
                                <label class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                    Discipline
                                    <span v-if="filteredBnccDisciplines.length > 0" class="ml-2 px-2 py-0.5 text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200 rounded-full">
                                        {{ filteredBnccDisciplines.length }} available
                                    </span>
                                </label>
                                <button
                                    v-if="selectedBnccDisciplines.length > 0"
                                    @click="selectedBnccDisciplines = []"
                                    class="text-xs text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors flex items-center"
                                >
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Clear
                                </button>
                            </div>
                            
                            <div v-if="!form.stage" class="p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                                <p class="text-sm text-yellow-800 dark:text-yellow-200 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Please select a stage first to see available disciplines
                                </p>
                            </div>
                            
                            <div v-else-if="filteredBnccDisciplines.length === 0" class="p-4 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg">
                                <p class="text-sm text-gray-600 dark:text-gray-400">No disciplines available for this stage</p>
                            </div>
                            
                            <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mt-3">
                                <label
                                    v-for="discipline in filteredBnccDisciplines"
                                    :key="discipline.id"
                                    class="relative flex items-center p-3 bg-white dark:bg-[#161615] border-2 rounded-lg cursor-pointer transition-all hover:shadow-md"
                                    :class="selectedBnccDisciplines.includes(discipline.id) 
                                        ? 'border-blue-500 bg-blue-50 dark:bg-blue-900/20 shadow-sm' 
                                        : 'border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600'"
                                >
                                    <input
                                        type="checkbox"
                                        :value="discipline.id"
                                        v-model="selectedBnccDisciplines"
                                        class="sr-only"
                                    />
                                    <div class="flex items-center flex-1">
                                        <div class="flex-shrink-0 w-5 h-5 border-2 rounded flex items-center justify-center mr-3 transition-all"
                                            :class="selectedBnccDisciplines.includes(discipline.id)
                                                ? 'border-blue-500 bg-blue-500'
                                                : 'border-gray-300 dark:border-gray-600'"
                                        >
                                            <svg v-if="selectedBnccDisciplines.includes(discipline.id)" class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                            {{ discipline.name }}
                                        </span>
                                    </div>
                                </label>
                            </div>
                            
                            <div v-if="selectedBnccDisciplines.length > 0" class="mt-3 flex flex-wrap gap-2">
                                <span class="text-xs text-gray-500 dark:text-gray-400">Selected:</span>
                                <span
                                    v-for="disciplineId in selectedBnccDisciplines"
                                    :key="disciplineId"
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-blue-100 dark:bg-blue-900 text-blue-800 dark:text-blue-200"
                                >
                                    {{ filteredBnccDisciplines.find(d => d.id === disciplineId)?.name }}
                                    <button
                                        @click="selectedBnccDisciplines = selectedBnccDisciplines.filter(id => id !== disciplineId)"
                                        class="ml-1.5 inline-flex items-center justify-center w-4 h-4 rounded-full hover:bg-blue-200 dark:hover:bg-blue-800 transition-colors"
                                    >
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                </span>
                            </div>
                        </div>

                        <!-- Unit Level - Card-based selection -->
                        <div v-if="selectedBnccDisciplines.length > 0" class="border-t border-gray-200 dark:border-gray-700 pt-4">
                            <div class="flex items-center justify-between mb-3">
                                <label class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    <svg class="w-5 h-5 mr-2 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                                    </svg>
                                    Unit
                                    <span v-if="filteredBnccUnits.length > 0" class="ml-2 px-2 py-0.5 text-xs font-medium bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 rounded-full">
                                        {{ filteredBnccUnits.length }} available
                                    </span>
                                    <span v-if="loadingUnits" class="ml-2 flex items-center text-xs text-blue-600 dark:text-blue-400">
                                        <svg class="animate-spin h-3 w-3 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Loading...
                                    </span>
                                </label>
                                <button
                                    v-if="selectedBnccUnits.length > 0"
                                    @click="selectedBnccUnits = []"
                                    class="text-xs text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors flex items-center"
                                >
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Clear
                                </button>
                            </div>
                            
                            <div v-if="loadingUnits" class="p-8 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg flex flex-col items-center justify-center">
                                <svg class="animate-spin h-8 w-8 text-indigo-600 dark:text-indigo-400 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span class="text-sm text-gray-600 dark:text-gray-400">Loading units...</span>
                            </div>
                            
                            <div v-else-if="filteredBnccUnits.length === 0" class="p-4 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg">
                                <p class="text-sm text-gray-600 dark:text-gray-400">No units available for selected disciplines</p>
                            </div>
                            
                            <div v-else class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3 mt-3">
                                <label
                                    v-for="unit in filteredBnccUnits"
                                    :key="unit.id"
                                    class="relative flex items-center p-3 bg-white dark:bg-[#161615] border-2 rounded-lg cursor-pointer transition-all hover:shadow-md"
                                    :class="selectedBnccUnits.includes(unit.id) 
                                        ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/20 shadow-sm' 
                                        : 'border-gray-200 dark:border-gray-700 hover:border-gray-300 dark:hover:border-gray-600'"
                                >
                                    <input
                                        type="checkbox"
                                        :value="unit.id"
                                        v-model="selectedBnccUnits"
                                        class="sr-only"
                                    />
                                    <div class="flex items-center flex-1">
                                        <div class="flex-shrink-0 w-5 h-5 border-2 rounded flex items-center justify-center mr-3 transition-all"
                                            :class="selectedBnccUnits.includes(unit.id)
                                                ? 'border-indigo-500 bg-indigo-500'
                                                : 'border-gray-300 dark:border-gray-600'"
                                        >
                                            <svg v-if="selectedBnccUnits.includes(unit.id)" class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            </svg>
                                        </div>
                                        <span class="text-sm font-medium text-gray-700 dark:text-gray-300">
                                            {{ unit.name }}
                                        </span>
                                    </div>
                                </label>
                            </div>
                            
                            <div v-if="selectedBnccUnits.length > 0" class="mt-3 flex flex-wrap gap-2">
                                <span class="text-xs text-gray-500 dark:text-gray-400">Selected:</span>
                                <span
                                    v-for="unitId in selectedBnccUnits"
                                    :key="unitId"
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200"
                                >
                                    {{ filteredBnccUnits.find(u => u.id === unitId)?.name }}
                                    <button
                                        @click="selectedBnccUnits = selectedBnccUnits.filter(id => id !== unitId)"
                                        class="ml-1.5 inline-flex items-center justify-center w-4 h-4 rounded-full hover:bg-indigo-200 dark:hover:bg-indigo-800 transition-colors"
                                    >
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                </span>
                            </div>
                        </div>

                        <!-- Knowledge Level - Inline list with search -->
                        <div v-if="selectedBnccUnits.length > 0" class="border-t border-gray-200 dark:border-gray-700 pt-4">
                            <div class="flex items-center justify-between mb-3">
                                <label class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    <svg class="w-5 h-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                    </svg>
                                    Knowledge
                                    <span v-if="filteredBnccKnowledges.length > 0" class="ml-2 px-2 py-0.5 text-xs font-medium bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200 rounded-full">
                                        {{ filteredBnccKnowledges.length }} available
                                        <span v-if="knowledgeSearch.trim() && filteredKnowledgesBySearch.length !== filteredBnccKnowledges.length" class="ml-1">
                                            ({{ filteredKnowledgesBySearch.length }} filtered)
                                        </span>
                                    </span>
                                    <span v-if="loadingKnowledges" class="ml-2 flex items-center text-xs text-blue-600 dark:text-blue-400">
                                        <svg class="animate-spin h-3 w-3 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Loading...
                                    </span>
                                </label>
                                <button
                                    v-if="selectedBnccKnowledges.length > 0"
                                    @click="selectedBnccKnowledges = []"
                                    class="text-xs text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors flex items-center"
                                >
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Clear
                                </button>
                            </div>
                            
                            <div v-if="loadingKnowledges" class="p-8 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg flex flex-col items-center justify-center">
                                <svg class="animate-spin h-8 w-8 text-purple-600 dark:text-purple-400 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span class="text-sm text-gray-600 dark:text-gray-400">Loading knowledges...</span>
                            </div>
                            
                            <div v-else-if="filteredBnccKnowledges.length === 0" class="p-4 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg">
                                <p class="text-sm text-gray-600 dark:text-gray-400">No knowledges available for selected units</p>
                            </div>
                            
                            <div v-else>
                                <!-- Search Input -->
                                <div class="mb-3">
                                    <div class="relative">
                                        <input
                                            type="text"
                                            v-model="knowledgeSearch"
                                            placeholder="Search knowledges..."
                                            class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#161615] text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:border-transparent transition-all"
                                        />
                                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                        <button
                                            v-if="knowledgeSearch.trim()"
                                            @click="knowledgeSearch = ''"
                                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- Knowledge List with Scroll -->
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-[#161615] max-h-96 overflow-y-auto">
                                    <div v-if="filteredKnowledgesBySearch.length === 0" class="p-8 text-center">
                                        <p class="text-sm text-gray-500 dark:text-gray-400">No knowledges match your search</p>
                                    </div>
                                    <div v-else class="divide-y divide-gray-200 dark:divide-gray-700">
                                        <label
                                            v-for="knowledge in filteredKnowledgesBySearch"
                                            :key="knowledge.id"
                                            class="flex items-center p-3 hover:bg-gray-50 dark:hover:bg-gray-800 cursor-pointer transition-colors"
                                            :class="selectedBnccKnowledges.includes(knowledge.id) ? 'bg-purple-50 dark:bg-purple-900/20' : ''"
                                        >
                                            <input
                                                type="checkbox"
                                                :value="knowledge.id"
                                                v-model="selectedBnccKnowledges"
                                                class="sr-only"
                                            />
                                            <div class="flex-shrink-0 w-5 h-5 border-2 rounded flex items-center justify-center mr-3 transition-all"
                                                :class="selectedBnccKnowledges.includes(knowledge.id)
                                                    ? 'border-purple-500 bg-purple-500'
                                                    : 'border-gray-300 dark:border-gray-600'"
                                            >
                                                <svg v-if="selectedBnccKnowledges.includes(knowledge.id)" class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                            <span class="text-sm font-medium text-gray-700 dark:text-gray-300 flex-1">
                                                {{ knowledge.name }}
                                            </span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div v-if="selectedBnccKnowledges.length > 0" class="mt-3 flex flex-wrap gap-2">
                                <span class="text-xs text-gray-500 dark:text-gray-400">Selected:</span>
                                <span
                                    v-for="knowledgeId in selectedBnccKnowledges"
                                    :key="knowledgeId"
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-200"
                                >
                                    {{ filteredBnccKnowledges.find(k => k.id === knowledgeId)?.name }}
                                    <button
                                        @click="selectedBnccKnowledges = selectedBnccKnowledges.filter(id => id !== knowledgeId)"
                                        class="ml-1.5 inline-flex items-center justify-center w-4 h-4 rounded-full hover:bg-purple-200 dark:hover:bg-purple-800 transition-colors"
                                    >
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                </span>
                            </div>
                        </div>

                        <!-- BNCC Level - Inline list with search -->
                        <div v-if="form.stage">
                            <div class="flex items-center justify-between mb-3">
                                <label class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300">
                                    <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                    BNCC
                                    <span v-if="filteredBnccs.length > 0" class="ml-2 px-2 py-0.5 text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200 rounded-full">
                                        {{ filteredBnccs.length }} available
                                        <span v-if="bnccSearch.trim() && filteredBnccsBySearch.length !== filteredBnccs.length" class="ml-1">
                                            ({{ filteredBnccsBySearch.length }} filtered)
                                        </span>
                                    </span>
                                    <span v-if="loadingBnccs" class="ml-2 flex items-center text-xs text-blue-600 dark:text-blue-400">
                                        <svg class="animate-spin h-3 w-3 mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Loading...
                                    </span>
                                </label>
                                <button
                                    v-if="form.bnccs.length > 0"
                                    @click="form.bnccs = []; bnccSearch = ''"
                                    class="text-xs text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors flex items-center"
                                >
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                    </svg>
                                    Clear
                                </button>
                            </div>
                            
                            <div v-if="loadingBnccs" class="p-8 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg flex flex-col items-center justify-center">
                                <svg class="animate-spin h-8 w-8 text-green-600 dark:text-green-400 mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span class="text-sm text-gray-600 dark:text-gray-400">Loading BNCCs...</span>
                            </div>
                            
                            <div v-else-if="filteredBnccs.length === 0" class="p-4 bg-gray-50 dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-lg">
                                <p class="text-sm text-gray-600 dark:text-gray-400">No BNCCs available for this stage</p>
                            </div>
                            
                            <div v-else>
                                <!-- Search Input -->
                                <div class="mb-3">
                                    <div class="relative">
                                        <input
                                            type="text"
                                            v-model="bnccSearch"
                                            placeholder="Search BNCCs by code or description..."
                                            class="w-full pl-10 pr-4 py-2.5 text-sm border border-gray-300 dark:border-gray-700 rounded-lg bg-white dark:bg-[#161615] text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all"
                                        />
                                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400 dark:text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                        <button
                                            v-if="bnccSearch.trim()"
                                            @click="bnccSearch = ''"
                                            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                
                                <!-- BNCC List with Scroll -->
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg bg-white dark:bg-[#161615] max-h-96 overflow-y-auto">
                                    <div v-if="filteredBnccsBySearch.length === 0" class="p-8 text-center">
                                        <p class="text-sm text-gray-500 dark:text-gray-400">No BNCCs match your search</p>
                                    </div>
                                    <div v-else class="divide-y divide-gray-200 dark:divide-gray-700">
                                        <label
                                            v-for="bncc in filteredBnccsBySearch"
                                            :key="bncc.id"
                                            class="flex items-center p-3 hover:bg-gray-50 dark:hover:bg-gray-800 cursor-pointer transition-colors"
                                            :class="form.bnccs.includes(bncc.id) ? 'bg-green-50 dark:bg-green-900/20' : ''"
                                        >
                                            <input
                                                type="checkbox"
                                                :value="bncc.id"
                                                v-model="form.bnccs"
                                                class="sr-only"
                                            />
                                            <div class="flex-shrink-0 w-5 h-5 border-2 rounded flex items-center justify-center mr-3 transition-all"
                                                :class="form.bnccs.includes(bncc.id)
                                                    ? 'border-green-500 bg-green-500'
                                                    : 'border-gray-300 dark:border-gray-600'"
                                            >
                                                <svg v-if="form.bnccs.includes(bncc.id)" class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            </div>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-start">
                                                    <span class="text-sm font-semibold text-gray-900 dark:text-gray-100 mr-2 flex-shrink-0">
                                                        {{ bncc.code }}
                                                    </span>
                                                    <span class="text-sm text-gray-700 dark:text-gray-300 flex-1">
                                                        {{ bncc.description }}
                                                    </span>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            
                            <div v-if="form.bnccs.length > 0" class="mt-3 flex flex-wrap gap-2">
                                <span class="text-xs text-gray-500 dark:text-gray-400">Selected:</span>
                                <span
                                    v-for="bnccId in form.bnccs"
                                    :key="bnccId"
                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200"
                                >
                                    {{ filteredBnccs.find(b => b.id === bnccId)?.code }}
                                    <button
                                        @click="form.bnccs = form.bnccs.filter(id => id !== bnccId)"
                                        class="ml-1.5 inline-flex items-center justify-center w-4 h-4 rounded-full hover:bg-green-200 dark:hover:bg-green-800 transition-colors"
                                    >
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path>
                                        </svg>
                                    </button>
                                </span>
                            </div>
                        </div>
                    </div>
                    <p v-if="errors['bnccs.*']" class="mt-3 text-sm text-red-600 dark:text-red-400 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        {{ errors['bnccs.*'][0] }}
                    </p>
                </div>

                <!-- Subjects Cascading Selection (Only for EM stage) -->
                <div v-if="showSubjects" class="bg-gray-50 dark:bg-[#0a0a0a] rounded-xl p-6 border border-gray-200 dark:border-gray-700 shadow-sm">
                    <div class="flex items-center justify-between mb-4">
                        <label class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300">
                            <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                            </svg>
                            Subjects
                        </label>
                        <button
                            v-if="selectedSubjectDisciplines.length > 0 || selectedSubjectTopics.length > 0 || selectedSubjectChapters.length > 0"
                            type="button"
                            @click="selectedSubjectDisciplines = []; selectedSubjectTopics = []; selectedSubjectChapters = []; form.subjects = []"
                            class="text-xs text-gray-500 dark:text-gray-400 hover:text-red-600 dark:hover:text-red-400 transition-colors"
                        >
                            Clear filters
                        </button>
                    </div>
                    
                    <div class="space-y-4">
                        <!-- Discipline Level -->
                        <div>
                            <label class="flex items-center text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">
                                <svg class="w-4 h-4 mr-1.5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                </svg>
                                Discipline <span v-if="filteredSubjectDisciplines.length > 0" class="ml-1 text-gray-400">({{ filteredSubjectDisciplines.length }})</span>
                            </label>
                            <select 
                                id="subject-disciplines-select"
                                v-model="selectedSubjectDisciplines" 
                                multiple 
                                class="multiselect w-full text-sm"
                            >
                                <option v-for="discipline in filteredSubjectDisciplines" :key="discipline.id" :value="discipline.id">
                                    {{ discipline.name }}
                                </option>
                            </select>
                            <p v-if="!form.stage" class="mt-1 text-xs text-gray-500 dark:text-gray-400">Select a stage first to see available disciplines</p>
                            <p v-else-if="filteredSubjectDisciplines.length === 0" class="mt-1 text-xs text-yellow-600 dark:text-yellow-400">No disciplines available for this stage</p>
                        </div>

                        <!-- Topic Level -->
                        <div v-if="selectedSubjectDisciplines.length > 0">
                            <label class="flex items-center text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">
                                <svg class="w-4 h-4 mr-1.5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                Topic <span v-if="filteredSubjectTopics.length > 0" class="ml-1 text-gray-400">({{ filteredSubjectTopics.length }})</span>
                            </label>
                            <select 
                                v-model="selectedSubjectTopics" 
                                multiple 
                                class="multiselect w-full text-sm"
                            >
                                <option v-for="topic in filteredSubjectTopics" :key="topic.id" :value="topic.id">
                                    {{ topic.name }}
                                </option>
                            </select>
                            <p v-if="filteredSubjectTopics.length === 0" class="mt-1 text-xs text-gray-500 dark:text-gray-400">No topics available</p>
                        </div>

                        <!-- Chapter Level -->
                        <div v-if="selectedSubjectTopics.length > 0">
                            <label class="flex items-center text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">
                                <svg class="w-4 h-4 mr-1.5 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Chapter <span v-if="filteredSubjectChapters.length > 0" class="ml-1 text-gray-400">({{ filteredSubjectChapters.length }})</span>
                            </label>
                            <select 
                                v-model="selectedSubjectChapters" 
                                multiple 
                                class="multiselect w-full text-sm"
                            >
                                <option v-for="chapter in filteredSubjectChapters" :key="chapter.id" :value="chapter.id">
                                    {{ chapter.name }}
                                </option>
                            </select>
                            <p v-if="filteredSubjectChapters.length === 0" class="mt-1 text-xs text-gray-500 dark:text-gray-400">No chapters available</p>
                        </div>

                        <!-- Subject Level -->
                        <div v-if="selectedSubjectChapters.length > 0">
                            <label class="flex items-center text-xs font-medium text-gray-600 dark:text-gray-400 mb-2">
                                <svg class="w-4 h-4 mr-1.5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                Subject <span v-if="filteredSubjects.length > 0" class="ml-1 text-gray-400">({{ filteredSubjects.length }})</span>
                            </label>
                            <select 
                                v-model="form.subjects" 
                                multiple 
                                class="multiselect w-full text-sm"
                            >
                                <option v-for="subject in filteredSubjects" :key="subject.id" :value="subject.id">
                                    {{ subject.name }}
                                </option>
                            </select>
                            <p v-if="filteredSubjects.length === 0" class="mt-1 text-xs text-gray-500 dark:text-gray-400">No subjects available</p>
                            <p v-if="form.subjects.length > 0" class="mt-2 text-xs text-green-600 dark:text-green-400">
                                {{ form.subjects.length }} Subject{{ form.subjects.length !== 1 ? 's' : '' }} selected
                            </p>
                        </div>
                    </div>
                    <p v-if="errors['subjects.*']" class="mt-3 text-sm text-red-600 dark:text-red-400 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                        </svg>
                        {{ errors['subjects.*'][0] }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Success/Error Messages -->
        <div v-if="successMessage" class="p-4 bg-green-100 dark:bg-green-900/20 border border-green-300 dark:border-green-700 rounded-lg text-green-800 dark:text-green-200">
            {{ successMessage }}
        </div>
        <div v-if="errorMessage" class="p-4 bg-red-100 dark:bg-red-900/20 border border-red-300 dark:border-red-700 rounded-lg text-red-800 dark:text-red-200">
            {{ errorMessage }}
        </div>

        <!-- Form Actions -->
        <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
            <a :href="cancelUrl" class="px-6 py-3 rounded-lg font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors flex items-center">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Cancel
            </a>
            <button 
                type="submit" 
                :disabled="loading || loadingUnits || loadingKnowledges || loadingBnccs"
                class="px-6 py-3 rounded-lg font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 transition-all shadow-md hover:shadow-lg flex items-center disabled:opacity-50 disabled:cursor-not-allowed relative overflow-hidden"
            >
                <span v-if="loading || loadingUnits || loadingKnowledges || loadingBnccs" class="absolute inset-0 bg-gradient-to-r from-blue-700 to-indigo-700 animate-pulse"></span>
                <svg v-if="loading || loadingUnits || loadingKnowledges || loadingBnccs" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white relative z-10" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <svg v-else class="w-5 h-5 mr-2 relative z-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                <span class="relative z-10">
                    {{ loading ? 'Creating...' : (loadingUnits || loadingKnowledges || loadingBnccs ? 'Loading data...' : 'Create Question') }}
                </span>
            </button>
        </div>
    </form>
</template>

<script>
import { ref, computed, reactive, watch, onMounted, nextTick } from 'vue'

export default {
    name: 'QuestionForm',
    props: {
        disciplines: {
            type: Array,
            default: () => []
        },
        units: {
            type: Array,
            default: () => []
        },
        knowledges: {
            type: Array,
            default: () => []
        },
        bnccs: {
            type: Array,
            default: () => []
        },
        topics: {
            type: Array,
            default: () => []
        },
        chapters: {
            type: Array,
            default: () => []
        },
        subjects: {
            type: Array,
            default: () => []
        },
        cancelUrl: {
            type: String,
            default: '/questions'
        }
    },
    setup(props) {
        const form = reactive({
            stem: '',
            answer_text: '',
            stage: '',
            type: '',
            status: '',
            bnccs: [],
            subjects: [],
            options: []
        })

        const loading = ref(false)
        const errors = ref({})
        const successMessage = ref('')
        const errorMessage = ref('')
        const optionsValidationError = ref('')
        const draggedIndex = ref(null)

        // BNCC hierarchy selections
        const selectedBnccDisciplines = ref([])
        const selectedBnccUnits = ref([])
        const selectedBnccKnowledges = ref([])

        // Subject hierarchy selections
        const selectedSubjectDisciplines = ref([])
        const selectedSubjectTopics = ref([])
        const selectedSubjectChapters = ref([])

        const showAnswerText = computed(() => form.type === 'open')
        const showOptions = computed(() => ['multiple_choice', 'multi_select', 'true_false'].includes(form.type))

        const hasCorrectAnswer = computed(() => {
            return form.options.some(opt => opt.is_correct === true)
        })

        // BNCC hierarchy - loaded via AJAX
        const filteredBnccDisciplines = computed(() => {
            if (!form.stage) return []
            return props.disciplines.filter(d => {
                const disciplineStage = d.stage?.value || d.stage
                // Include disciplines that match the stage OR have null stage (apply to all stages)
                return disciplineStage === form.stage || disciplineStage === null || disciplineStage === undefined
            })
        })

        const filteredBnccUnits = ref([])
        const filteredBnccKnowledges = ref([])
        const filteredBnccs = ref([])
        const loadingUnits = ref(false)
        const loadingKnowledges = ref(false)
        const loadingBnccs = ref(false)
        
        // Search filter for knowledges
        const knowledgeSearch = ref('')
        
        // Filtered knowledges based on search
        const filteredKnowledgesBySearch = computed(() => {
            if (!knowledgeSearch.value.trim()) {
                return filteredBnccKnowledges.value
            }
            const searchTerm = knowledgeSearch.value.toLowerCase().trim()
            return filteredBnccKnowledges.value.filter(k => 
                k.name.toLowerCase().includes(searchTerm)
            )
        })
        
        // Search filter for BNCCs
        const bnccSearch = ref('')
        
        // Filtered BNCCs based on search
        const filteredBnccsBySearch = computed(() => {
            if (!bnccSearch.value.trim()) {
                return filteredBnccs.value
            }
            const searchTerm = bnccSearch.value.toLowerCase().trim()
            return filteredBnccs.value.filter(b => {
                const codeMatch = b.code?.toLowerCase().includes(searchTerm) || false
                const descMatch = b.description?.toLowerCase().includes(searchTerm) || false
                return codeMatch || descMatch
            })
        })
        
        // Computed property to show/hide Subject section
        const showSubjects = computed(() => form.stage === 'EM')

        // Subject hierarchy filtering
        const filteredSubjectDisciplines = computed(() => {
            if (!form.stage) return []
            return props.disciplines.filter(d => {
                const disciplineStage = d.stage?.value || d.stage
                // Include disciplines that match the stage OR have null stage (apply to all stages)
                return disciplineStage === form.stage || disciplineStage === null || disciplineStage === undefined
            })
        })

        const filteredSubjectTopics = computed(() => {
            if (selectedSubjectDisciplines.value.length === 0) return []
            const disciplineIds = selectedSubjectDisciplines.value
            return props.topics.filter(t => disciplineIds.includes(t.discipline_id))
        })

        const filteredSubjectChapters = computed(() => {
            if (selectedSubjectTopics.value.length === 0) return []
            const topicIds = selectedSubjectTopics.value
            return props.chapters.filter(c => topicIds.includes(c.topic_id))
        })

        const filteredSubjects = computed(() => {
            if (selectedSubjectChapters.value.length === 0) return []
            const chapterIds = selectedSubjectChapters.value
            return props.subjects.filter(s => chapterIds.includes(s.chapter_id))
        })

        // Watch for type changes to clear/initialize options
        watch(() => form.type, (newType, oldType) => {
            // Clear options when type changes
            if (oldType && oldType !== newType) {
                form.options = []
            }
            
            if (newType === 'true_false') {
                form.options = [
                    { text: 'True', is_correct: false, order: 1 },
                    { text: 'False', is_correct: false, order: 2 }
                ]
            } else if (newType === 'open') {
                form.options = []
            }
            // Recalculate order when type changes
            form.options.forEach((opt, idx) => {
                opt.order = idx + 1
            })
            validateOptions()
        })

        // Watch for stage changes to reset hierarchy selections
        watch(() => form.stage, (newStage, oldStage) => {
            if (oldStage && oldStage !== newStage) {
                // Reset BNCC hierarchy
                selectedBnccDisciplines.value = []
                selectedBnccUnits.value = []
                selectedBnccKnowledges.value = []
                form.bnccs = []
                bnccSearch.value = ''
                
                // Reset Subject hierarchy
                selectedSubjectDisciplines.value = []
                selectedSubjectTopics.value = []
                selectedSubjectChapters.value = []
                form.subjects = []
            }
        })

        // Load units via AJAX when disciplines are selected
        const loadUnits = async () => {
            if (selectedBnccDisciplines.value.length === 0) {
                filteredBnccUnits.value = []
                selectedBnccUnits.value = []
                return
            }

            loadingUnits.value = true
            try {
                const response = await window.axios.post('/questions/ajax/units', {
                    discipline_ids: selectedBnccDisciplines.value,
                    stage: form.stage
                })
                filteredBnccUnits.value = Array.isArray(response.data) ? response.data : []
            } catch (error) {
                console.error('Error loading units:', error)
                filteredBnccUnits.value = []
            } finally {
                loadingUnits.value = false
            }
        }

        // Load knowledges via AJAX when units are selected
        const loadKnowledges = async () => {
            if (selectedBnccUnits.value.length === 0) {
                filteredBnccKnowledges.value = []
                selectedBnccKnowledges.value = []
                knowledgeSearch.value = ''
                return
            }

            loadingKnowledges.value = true
            try {
                const response = await window.axios.post('/questions/ajax/knowledges', {
                    unit_ids: selectedBnccUnits.value
                })
                filteredBnccKnowledges.value = Array.isArray(response.data) ? response.data : []
                knowledgeSearch.value = '' // Reset search when new knowledges load
            } catch (error) {
                console.error('Error loading knowledges:', error)
                filteredBnccKnowledges.value = []
            } finally {
                loadingKnowledges.value = false
            }
        }

        // Load BNCCs via AJAX when knowledges are selected or stage changes
        const loadBnccs = async () => {
            if (!form.stage) {
                filteredBnccs.value = []
                return
            }

            loadingBnccs.value = true
            try {
                const response = await window.axios.post('/questions/ajax/bnccs', {
                    knowledge_ids: selectedBnccKnowledges.value.length > 0 ? selectedBnccKnowledges.value : [],
                    stage: form.stage
                })
                filteredBnccs.value = Array.isArray(response.data) ? response.data : []
                bnccSearch.value = '' // Reset search when new BNCCs load
            } catch (error) {
                console.error('Error loading BNCCs:', error)
                filteredBnccs.value = []
            } finally {
                loadingBnccs.value = false
            }
        }

        // Watch BNCC hierarchy - clear lower levels when higher levels change and load data
        watch(() => selectedBnccDisciplines.value, () => {
            selectedBnccUnits.value = []
            selectedBnccKnowledges.value = []
            form.bnccs = []
            loadUnits()
        })
        watch(() => selectedBnccUnits.value, () => {
            selectedBnccKnowledges.value = []
            form.bnccs = []
            knowledgeSearch.value = '' // Clear search when units change
            loadKnowledges()
        })
        watch(() => selectedBnccKnowledges.value, () => {
            form.bnccs = []
            loadBnccs()
        })
        watch(() => form.stage, () => {
            // Reload all BNCC hierarchy when stage changes
            if (selectedBnccDisciplines.value.length > 0) {
                loadUnits()
            } else if (form.stage) {
                // Load BNCCs directly when stage is selected (for direct selection)
                loadBnccs()
            } else {
                filteredBnccs.value = []
            }
        })

        // Watch Subject hierarchy - clear lower levels when higher levels change
        watch(() => selectedSubjectDisciplines.value, () => {
            selectedSubjectTopics.value = []
            selectedSubjectChapters.value = []
            form.subjects = []
        })
        watch(() => selectedSubjectTopics.value, () => {
            selectedSubjectChapters.value = []
            form.subjects = []
        })
        watch(() => selectedSubjectChapters.value, () => {
            form.subjects = []
        })

        // Watch for BNCC selection changes to auto-populate hierarchy
        watch(() => form.bnccs, (newBnccs, oldBnccs) => {
            if (!newBnccs || newBnccs.length === 0) return
            
            // Get newly added BNCCs
            const addedBnccs = newBnccs.filter(id => !oldBnccs || !oldBnccs.includes(id))
            
            addedBnccs.forEach(bnccId => {
                const bncc = props.bnccs.find(b => b.id === bnccId)
                if (!bncc) return
                
                // Auto-populate discipline if BNCC has one
                if (bncc.discipline && bncc.discipline.id) {
                    if (!selectedBnccDisciplines.value.includes(bncc.discipline.id)) {
                        selectedBnccDisciplines.value.push(bncc.discipline.id)
                    }
                }
                
                // Auto-populate knowledges if BNCC has any
                if (bncc.knowledges && bncc.knowledges.length > 0) {
                    bncc.knowledges.forEach(knowledge => {
                        if (!selectedBnccKnowledges.value.includes(knowledge.id)) {
                            selectedBnccKnowledges.value.push(knowledge.id)
                        }
                        
                        // Auto-populate unit if knowledge has one
                        if (knowledge.unit && knowledge.unit.id) {
                            const unit = props.units.find(u => u.id === knowledge.unit.id)
                            if (unit && unit.discipline_id) {
                                // Make sure the discipline is selected
                                if (!selectedBnccDisciplines.value.includes(unit.discipline_id)) {
                                    selectedBnccDisciplines.value.push(unit.discipline_id)
                                }
                                // Add the unit
                                if (!selectedBnccUnits.value.includes(unit.id)) {
                                    selectedBnccUnits.value.push(unit.id)
                                }
                            }
                        }
                    })
                }
            })
        }, { deep: true })

        // Initialize Tom Select for multiselect fields
        const initializeTomSelect = () => {
            nextTick(() => {
                if (typeof window.TomSelect !== 'undefined') {
                    const multiselectFields = document.querySelectorAll('.multiselect:not(.ts-hidden-accessible)')
                    multiselectFields.forEach((field) => {
                        // Check if already initialized
                        if (!field.tomselect) {
                            new window.TomSelect(field, {
                                plugins: ['remove_button'],
                                placeholder: 'Select options...',
                                maxItems: null,
                                create: false,
                                searchField: ['text', 'value'],
                                render: {
                                    no_results: function(data, escape) {
                                        return '<div class="no-results">No results found for "' + escape(data.input) + '"</div>'
                                    }
                                }
                            })
                        }
                    })
                }
            })
        }

        // Initialize on mount
        onMounted(() => {
            initializeTomSelect()
            // Load BNCCs if stage is already selected (for direct selection)
            if (form.stage) {
                loadBnccs()
            }
        })

        // Re-initialize when stage changes
        watch(() => form.stage, () => {
            nextTick(() => {
                initializeTomSelect()
            })
        })
        
        // Update Tom Select when filtered options change
        watch(() => filteredBnccDisciplines.value, () => {
            nextTick(() => {
                const field = document.querySelector('#bncc-disciplines-select')
                if (field && field.tomselect) {
                    field.tomselect.clearOptions()
                    filteredBnccDisciplines.value.forEach(d => {
                        field.tomselect.addOption({ value: d.id, text: d.name })
                    })
                    field.tomselect.refreshOptions(false)
                } else if (field) {
                    initializeTomSelect('#bncc-disciplines-select')
                }
            })
        })
        
        watch(() => filteredSubjectDisciplines.value, () => {
            nextTick(() => {
                const field = document.querySelector('#subject-disciplines-select')
                if (field && field.tomselect) {
                    field.tomselect.clearOptions()
                    filteredSubjectDisciplines.value.forEach(d => {
                        field.tomselect.addOption({ value: d.id, text: d.name })
                    })
                    field.tomselect.refreshOptions(false)
                } else if (field) {
                    initializeTomSelect('#subject-disciplines-select')
                }
            })
        })
        
        // Update Tom Select when filtered options change
        watch(() => filteredBnccUnits.value, () => {
            nextTick(() => {
                const field = document.querySelector('#bncc-units-select')
                if (field && field.tomselect) {
                    field.tomselect.clearOptions()
                    filteredBnccUnits.value.forEach(u => {
                        field.tomselect.addOption({ value: u.id, text: u.name })
                    })
                    field.tomselect.refreshOptions(false)
                } else if (field) {
                    initializeTomSelect('#bncc-units-select')
                }
            })
        })
        
        watch(() => filteredBnccKnowledges.value, () => {
            nextTick(() => {
                const field = document.querySelector('#bncc-knowledges-select')
                if (field && field.tomselect) {
                    field.tomselect.clearOptions()
                    filteredBnccKnowledges.value.forEach(k => {
                        field.tomselect.addOption({ value: k.id, text: k.name })
                    })
                    field.tomselect.refreshOptions(false)
                } else if (field) {
                    initializeTomSelect('#bncc-knowledges-select')
                }
            })
        })
        
        // No need to update Tom Select for BNCCs - using card-based selection

        const addOption = () => {
            if (form.type === 'true_false') return
            const newOrder = form.options.length > 0 ? Math.max(...form.options.map(o => o.order || 0)) + 1 : 1
            form.options.push({ text: '', is_correct: false, order: newOrder })
            updateOptions()
        }

        const removeOption = (index) => {
            if (form.type === 'true_false') return
            form.options.splice(index, 1)
            // Recalculate order after removal (maintain sequential order)
            form.options.forEach((opt, idx) => {
                opt.order = idx + 1
            })
            validateOptions()
        }

        const moveOption = (index, direction) => {
            if (form.type === 'true_false') return
            const newIndex = direction === 'up' ? index - 1 : index + 1
            if (newIndex >= 0 && newIndex < form.options.length) {
                const [movedOption] = form.options.splice(index, 1)
                form.options.splice(newIndex, 0, movedOption)
                // Update order based on new position
                form.options.forEach((opt, i) => {
                    opt.order = i + 1
                })
                validateOptions()
            }
        }

        const handleCorrectChange = (index) => {
            if (form.type === 'multiple_choice' || form.type === 'true_false') {
                form.options.forEach((opt, idx) => {
                    if (idx !== index) {
                        opt.is_correct = false
                    }
                })
            }
            updateOptions()
            validateOptions()
        }

        const validateOptions = () => {
            optionsValidationError.value = ''

            if (form.options.length < 2) {
                optionsValidationError.value = 'At least 2 options are required.'
                return false
            }

            if (form.type === 'true_false' && form.options.length !== 2) {
                optionsValidationError.value = 'True/False questions must have exactly 2 options.'
                return false
            }

            const correctCount = form.options.filter(opt => opt.is_correct).length

            if (form.type === 'multiple_choice' || form.type === 'true_false') {
                if (correctCount !== 1) {
                    optionsValidationError.value = 'Exactly one option must be marked as correct.'
                    return false
                }
            } else if (form.type === 'multi_select') {
                if (correctCount < 1) {
                    optionsValidationError.value = 'At least one option must be marked as correct.'
                    return false
                }
            }

            const emptyOptions = form.options.filter(opt => !opt.text || opt.text.trim() === '')
            if (emptyOptions.length > 0) {
                optionsValidationError.value = 'All options must have text.'
                return false
            }

            return true
        }

        const updateOptions = () => {
            form.options.forEach((opt, idx) => {
                if (!opt.order) {
                    opt.order = idx + 1
                }
            })
            validateOptions()
        }

        // Drag and drop handlers
        const dragStart = (index, event) => {
            if (form.type === 'true_false') return
            draggedIndex.value = index
            event.dataTransfer.effectAllowed = 'move'
            event.dataTransfer.setData('text/html', event.target.outerHTML)
        }

        const dragOver = (event) => {
            event.preventDefault()
            event.dataTransfer.dropEffect = 'move'
        }

        const drop = (dropIndex, event) => {
            event.preventDefault()
            if (draggedIndex.value === null || draggedIndex.value === dropIndex) {
                draggedIndex.value = null
                return
            }

            const draggedOption = form.options[draggedIndex.value]
            form.options.splice(draggedIndex.value, 1)
            form.options.splice(dropIndex, 0, draggedOption)
            
            // Update order numbers based on new position (drag-and-drop is the only way to change order)
            form.options.forEach((opt, idx) => {
                opt.order = idx + 1
            })
            
            draggedIndex.value = null
            validateOptions()
        }

        const submitForm = async () => {
            loading.value = true
            errors.value = {}
            successMessage.value = ''
            errorMessage.value = ''

            try {
                const formData = {
                    stem: form.stem,
                    stage: form.stage,
                    type: form.type,
                    status: form.status,
                    bnccs: form.bnccs,
                    subjects: form.subjects
                }

                // Add answer_text or options based on type
                if (form.type === 'open') {
                    formData.answer_text = form.answer_text
                } else {
                    formData.options = form.options
                }

                const response = await window.axios.post('/questions', formData)

                if (response.data.success) {
                    successMessage.value = response.data.message || 'Question created successfully.'
                    // Redirect after a short delay
                    setTimeout(() => {
                        window.location.href = props.cancelUrl
                    }, 1500)
                }
            } catch (error) {
                if (error.response && error.response.status === 422) {
                    // Validation errors
                    errors.value = error.response.data.errors || {}
                } else {
                    errorMessage.value = error.response?.data?.message || 'An error occurred while creating the question.'
                }
            } finally {
                loading.value = false
            }
        }

        return {
            form,
            loading,
            errors,
            successMessage,
            errorMessage,
            optionsValidationError,
            draggedIndex,
            showAnswerText,
            showOptions,
            hasCorrectAnswer,
            addOption,
            removeOption,
            moveOption,
            handleCorrectChange,
            updateOptions,
            dragStart,
            dragOver,
            drop,
            submitForm,
            // BNCC hierarchy
            selectedBnccDisciplines,
            selectedBnccUnits,
            selectedBnccKnowledges,
            filteredBnccDisciplines,
            filteredBnccUnits,
            filteredBnccKnowledges,
            filteredBnccs,
            loadingUnits,
            loadingKnowledges,
            loadingBnccs,
            knowledgeSearch,
            filteredKnowledgesBySearch,
            bnccSearch,
            filteredBnccsBySearch,
            // Subject hierarchy
            selectedSubjectDisciplines,
            selectedSubjectTopics,
            selectedSubjectChapters,
            filteredSubjectDisciplines,
            filteredSubjectTopics,
            filteredSubjectChapters,
            filteredSubjects,
            showSubjects,
            // Props
            disciplines: props.disciplines,
            units: props.units,
            knowledges: props.knowledges,
            bnccs: props.bnccs,
            topics: props.topics,
            chapters: props.chapters,
            subjects: props.subjects,
            cancelUrl: props.cancelUrl
        }
    }
}
</script>

