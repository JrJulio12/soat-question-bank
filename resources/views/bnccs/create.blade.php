@extends('layouts.app')

@section('title', 'Create BNCC')

@section('content')
<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">Create BNCC</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Add a new BNCC to the system</p>
        </div>

        <div class="bg-white dark:bg-[#161615] overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <form action="{{ route('bnccs.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Code <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="code" id="code" value="{{ old('code') }}" required
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-[#0a0a0a] dark:text-[#EDEDEC] shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('code') border-red-500 @enderror">
                        @error('code')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Description
                        </label>
                        <textarea name="description" id="description" rows="3"
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-[#0a0a0a] dark:text-[#EDEDEC] shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                        @error('description')
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
                            <option value="EF" {{ old('stage') == 'EF' ? 'selected' : '' }}>EF</option>
                            <option value="EM" {{ old('stage') == 'EM' ? 'selected' : '' }}>EM</option>
                        </select>
                        @error('stage')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="discipline_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Discipline <span class="text-red-500">*</span>
                        </label>
                        <select name="discipline_id" id="discipline_id" required
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-[#0a0a0a] dark:text-[#EDEDEC] shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('discipline_id') border-red-500 @enderror">
                            <option value="">Select a discipline</option>
                            @foreach($disciplines as $discipline)
                                <option value="{{ $discipline->id }}" {{ old('discipline_id') == $discipline->id ? 'selected' : '' }}>
                                    {{ $discipline->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('discipline_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Series
                        </label>
                        <div class="max-h-48 overflow-y-auto border border-gray-300 dark:border-gray-700 rounded-md p-2">
                            @foreach($series as $serie)
                                <label class="flex items-center mb-2">
                                    <input type="checkbox" name="series[]" value="{{ $serie->id }}" {{ in_array($serie->id, old('series', [])) ? 'checked' : '' }}
                                        class="rounded border-gray-300 dark:border-gray-700 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ $serie->name }} ({{ $serie->stage->value }})</span>
                                </label>
                            @endforeach
                        </div>
                        @error('series.*')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Knowledges
                        </label>
                        <div class="max-h-48 overflow-y-auto border border-gray-300 dark:border-gray-700 rounded-md p-2">
                            @foreach($knowledges as $knowledge)
                                <label class="flex items-center mb-2">
                                    <input type="checkbox" name="knowledges[]" value="{{ $knowledge->id }}" {{ in_array($knowledge->id, old('knowledges', [])) ? 'checked' : '' }}
                                        class="rounded border-gray-300 dark:border-gray-700 text-blue-600 focus:ring-blue-500">
                                    <span class="ml-2 text-sm text-gray-700 dark:text-gray-300">{{ Str::limit($knowledge->name, 80) }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('knowledges.*')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                            Create BNCC
                        </button>
                        <a href="{{ route('bnccs.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

