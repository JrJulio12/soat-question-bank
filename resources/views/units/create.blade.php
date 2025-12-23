@extends('layouts.app')

@section('title', 'Create Unit')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC] mb-2">Create Unit</h1>
            <p class="text-sm text-gray-600 dark:text-gray-400">Add a new unit to the system</p>
        </div>

        <div class="bg-white dark:bg-[#161615] overflow-hidden shadow-lg sm:rounded-xl border border-gray-200 dark:border-gray-700">
            <div class="p-8">
                <form action="{{ route('units.store') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label for="name" class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                            </svg>
                            Name <span class="text-red-500 ml-1">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-[#0a0a0a] dark:text-[#EDEDEC] shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all @error('name') border-red-500 focus:ring-red-500/20 @enderror">
                        @error('name')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center animate-pulse">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div>
                        <label for="discipline_id" class="flex items-center text-sm font-semibold text-gray-700 dark:text-gray-300 mb-2">
                            <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                            </svg>
                            Discipline <span class="text-red-500 ml-1">*</span>
                        </label>
                        <select name="discipline_id" id="discipline_id" required
                            class="w-full px-4 py-3 rounded-lg border border-gray-300 dark:border-gray-700 dark:bg-[#0a0a0a] dark:text-[#EDEDEC] shadow-sm focus:border-blue-500 focus:ring-2 focus:ring-blue-500/20 transition-all @error('discipline_id') border-red-500 focus:ring-red-500/20 @enderror">
                            <option value="">Select a discipline</option>
                            @foreach($disciplines as $discipline)
                                <option value="{{ $discipline->id }}" {{ old('discipline_id') == $discipline->id ? 'selected' : '' }}>
                                    {{ $discipline->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('discipline_id')
                            <p class="mt-2 text-sm text-red-600 dark:text-red-400 flex items-center animate-pulse">
                                <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200 dark:border-gray-700">
                        <a href="{{ route('units.index') }}" class="px-6 py-3 rounded-lg font-medium text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-800 hover:bg-gray-200 dark:hover:bg-gray-700 transition-colors flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                            Cancel
                        </a>
                        <button type="submit" class="px-6 py-3 rounded-lg font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 transition-all shadow-md hover:shadow-lg flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Create Unit
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
