@extends('layouts.app')

@section('title', 'Edit Serie')

@section('content')
<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">Edit Serie</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Update serie information</p>
        </div>

        <div class="bg-white dark:bg-[#161615] overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <form action="{{ route('series.update', $serie) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Name <span class="text-red-500">*</span>
                        </label>
                        <input type="text" name="name" id="name" value="{{ old('name', $serie->name) }}" required
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-[#0a0a0a] dark:text-[#EDEDEC] shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-500 @enderror">
                        @error('name')
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
                            <option value="EF" {{ old('stage', $serie->stage->value) == 'EF' ? 'selected' : '' }}>EF</option>
                            <option value="EM" {{ old('stage', $serie->stage->value) == 'EM' ? 'selected' : '' }}>EM</option>
                        </select>
                        @error('stage')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="order" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Order
                        </label>
                        <input type="number" name="order" id="order" value="{{ old('order', $serie->order) }}" min="1"
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-[#0a0a0a] dark:text-[#EDEDEC] shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('order') border-red-500 @enderror">
                        @error('order')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                            Update Serie
                        </button>
                        <a href="{{ route('series.show', $serie) }}" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

