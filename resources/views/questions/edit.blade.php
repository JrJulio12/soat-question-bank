@extends('layouts.app')

@section('title', 'Edit Question')

@section('content')
<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">Edit Question</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Update question information</p>
        </div>

        <div class="bg-white dark:bg-[#161615] overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <form action="{{ route('questions.update', $question) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="stem" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Stem <span class="text-red-500">*</span>
                        </label>
                        <textarea name="stem" id="stem" rows="4" required
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-[#0a0a0a] dark:text-[#EDEDEC] shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('stem') border-red-500 @enderror">{{ old('stem', $question->stem) }}</textarea>
                        @error('stem')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="answer_text" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Answer Text
                        </label>
                        <textarea name="answer_text" id="answer_text" rows="3"
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-[#0a0a0a] dark:text-[#EDEDEC] shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('answer_text') border-red-500 @enderror">{{ old('answer_text', $question->answer_text) }}</textarea>
                        @error('answer_text')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="stage" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Stage <span class="text-red-500">*</span>
                        </label>
                        <select name="stage" id="stage" required
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-[#0a0a0a] dark:text-[#EDEDEC] shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('stage') border-red-500 @enderror">
                            <option value="">Select a stage</option>
                            <option value="EF" {{ old('stage', $question->stage->value) == 'EF' ? 'selected' : '' }}>EF</option>
                            <option value="EM" {{ old('stage', $question->stage->value) == 'EM' ? 'selected' : '' }}>EM</option>
                        </select>
                        @error('stage')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Type <span class="text-red-500">*</span>
                        </label>
                        <select name="type" id="type" required
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-[#0a0a0a] dark:text-[#EDEDEC] shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('type') border-red-500 @enderror">
                            <option value="">Select a type</option>
                            <option value="multiple_choice" {{ old('type', $question->type->value) == 'multiple_choice' ? 'selected' : '' }}>Multiple Choice</option>
                            <option value="multi_select" {{ old('type', $question->type->value) == 'multi_select' ? 'selected' : '' }}>Multi Select</option>
                            <option value="true_false" {{ old('type', $question->type->value) == 'true_false' ? 'selected' : '' }}>True/False</option>
                            <option value="open" {{ old('type', $question->type->value) == 'open' ? 'selected' : '' }}>Open</option>
                        </select>
                        @error('type')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select name="status" id="status" required
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-[#0a0a0a] dark:text-[#EDEDEC] shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('status') border-red-500 @enderror">
                            <option value="">Select a status</option>
                            <option value="draft" {{ old('status', $question->status->value) == 'draft' ? 'selected' : '' }}>Draft</option>
                            <option value="published" {{ old('status', $question->status->value) == 'published' ? 'selected' : '' }}>Published</option>
                        </select>
                        @error('status')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            BNCCs
                        </label>
                        <div class="max-h-48 overflow-y-auto border border-gray-300 dark:border-gray-700 rounded-md p-2">
                            @foreach($bnccs as $bncc)
                                <label class="flex items-center mb-2">
                                    <input type="checkbox" name="bnccs[]" value="{{ $bncc->id }}" {{ in_array($bncc->id, old('bnccs', $question->bnccs->pluck('id')->toArray())) ? 'checked' : '' }}
                                        class="rounded border-gray-300 dark:border-gray-700 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $bncc->code }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('bnccs.*')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Subjects
                        </label>
                        <div class="max-h-48 overflow-y-auto border border-gray-300 dark:border-gray-700 rounded-md p-2">
                            @foreach($subjects as $subject)
                                <label class="flex items-center mb-2">
                                    <input type="checkbox" name="subjects[]" value="{{ $subject->id }}" {{ in_array($subject->id, old('subjects', $question->subjects->pluck('id')->toArray())) ? 'checked' : '' }}
                                        class="rounded border-gray-300 dark:border-gray-700 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $subject->name }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('subjects.*')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                            Update Question
                        </button>
                        <a href="{{ route('questions.show', $question) }}" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

