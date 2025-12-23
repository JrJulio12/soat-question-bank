@extends('layouts.app')

@section('title', 'Create Knowledge')

@section('content')
<div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">Create Knowledge</h1>
            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Add a new knowledge to the system</p>
        </div>

        <div class="bg-white dark:bg-[#161615] overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                <form action="{{ route('knowledges.store') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Name <span class="text-red-500">*</span>
                        </label>
                        <textarea name="name" id="name" rows="3" required
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-[#0a0a0a] dark:text-[#EDEDEC] shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('name') border-red-500 @enderror">{{ old('name') }}</textarea>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="unit_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Unit <span class="text-red-500">*</span>
                        </label>
                        <select name="unit_id" id="unit_id" required
                            class="w-full rounded-md border-gray-300 dark:border-gray-700 dark:bg-[#0a0a0a] dark:text-[#EDEDEC] shadow-sm focus:border-blue-500 focus:ring-blue-500 @error('unit_id') border-red-500 @enderror">
                            <option value="">Select a unit</option>
                            @foreach($units as $unit)
                                <option value="{{ $unit->id }}" {{ old('unit_id') == $unit->id ? 'selected' : '' }}>
                                    {{ $unit->name }} ({{ $unit->discipline->name }})
                                </option>
                            @endforeach
                        </select>
                        @error('unit_id')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                            Create Knowledge
                        </button>
                        <a href="{{ route('knowledges.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

