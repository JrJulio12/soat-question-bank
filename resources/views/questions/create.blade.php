@extends('layouts.app')

@section('title', 'Create Question')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">Create Question</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400">Add a new question to the system</p>
        </div>

        <div class="bg-white dark:bg-[#161615] overflow-hidden shadow-lg sm:rounded-xl border border-gray-200 dark:border-gray-700">
            <div class="p-8">
                <form action="{{ route('questions.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <!-- Stem Field -->
                    <div>
                        <label for="stem" class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Stem <span class="text-red-500 ml-1">*</span>
                        </label>
                        <textarea name="stem" id="stem" rows="5" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-[#0a0a0a] dark:text-[#EDEDEC] shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all resize-none @error('stem') border-red-500 focus:ring-red-500/20 @enderror">{{ old('stem') }}</textarea>
                        @error('stem')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center animate-pulse">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <!-- Answer Text Field -->
                    <div>
                        <label for="answer_text" class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Answer Text
                        </label>
                        <textarea name="answer_text" id="answer_text" rows="4"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-[#0a0a0a] dark:text-[#EDEDEC] shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all resize-none @error('answer_text') border-red-500 focus:ring-red-500/20 @enderror">{{ old('answer_text') }}</textarea>
                        @error('answer_text')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center animate-pulse">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- Stage Field -->
                        <div>
                            <label for="stage" class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                                Stage <span class="text-red-500 ml-1">*</span>
                            </label>
                            <select name="stage" id="stage" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-[#0a0a0a] dark:text-[#EDEDEC] shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all @error('stage') border-red-500 focus:ring-red-500/20 @enderror">
                                <option value="">Select a stage</option>
                                <option value="EF" {{ old('stage') == 'EF' ? 'selected' : '' }}>EF</option>
                                <option value="EM" {{ old('stage') == 'EM' ? 'selected' : '' }}>EM</option>
                            </select>
                            @error('stage')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center animate-pulse">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Type Field -->
                        <div>
                            <label for="type" class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                </svg>
                                Type <span class="text-red-500 ml-1">*</span>
                            </label>
                            <select name="type" id="type" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-[#0a0a0a] dark:text-[#EDEDEC] shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all @error('type') border-red-500 focus:ring-red-500/20 @enderror">
                                <option value="">Select a type</option>
                                <option value="multiple_choice" {{ old('type') == 'multiple_choice' ? 'selected' : '' }}>Multiple Choice</option>
                                <option value="multi_select" {{ old('type') == 'multi_select' ? 'selected' : '' }}>Multi Select</option>
                                <option value="true_false" {{ old('type') == 'true_false' ? 'selected' : '' }}>True/False</option>
                                <option value="open" {{ old('type') == 'open' ? 'selected' : '' }}>Open</option>
                            </select>
                            @error('type')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center animate-pulse">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        <!-- Status Field -->
                        <div>
                            <label for="status" class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                                </svg>
                                Status <span class="text-red-500 ml-1">*</span>
                            </label>
                            <select name="status" id="status" required
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-[#0a0a0a] dark:text-[#EDEDEC] shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all @error('status') border-red-500 focus:ring-red-500/20 @enderror">
                                <option value="">Select a status</option>
                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                            </select>
                            @error('status')
                                <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center animate-pulse">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                    </svg>
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>
                    </div>

                    <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Relationships</h3>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- BNCCs Multiselect -->
                            <div>
                                <label for="bnccs" class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    BNCCs
                                </label>
                                <select name="bnccs[]" id="bnccs" multiple class="multiselect w-full">
                                    @foreach($bnccs as $bncc)
                                        <option value="{{ $bncc->id }}" {{ in_array($bncc->id, old('bnccs', [])) ? 'selected' : '' }}>
                                            {{ $bncc->code }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('bnccs.*')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center animate-pulse">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>

                            <!-- Subjects Multiselect -->
                            <div>
                                <label for="subjects" class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                                    <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                    </svg>
                                    Subjects
                                </label>
                                <select name="subjects[]" id="subjects" multiple class="multiselect w-full">
                                    @foreach($subjects as $subject)
                                        <option value="{{ $subject->id }}" {{ in_array($subject->id, old('subjects', [])) ? 'selected' : '' }}>
                                            {{ $subject->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('subjects.*')
                                    <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center animate-pulse">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $message }}
                                    </p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('questions.index') }}" class="px-6 py-3 rounded-lg font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-3 rounded-lg font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 transition-all shadow-md hover:shadow-lg flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Create Question
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
