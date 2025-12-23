@extends('layouts.app')

@section('title', 'Topic Details')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">{{ $topic->name }}</h1>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Topic Details</p>
            </div>
            <div class="flex gap-3">
                <a href="{{ route('topics.edit', $topic) }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                    Edit
                </a>
                <a href="{{ route('topics.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                    Back
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white dark:bg-[#161615] overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-xl font-semibold mb-4 text-[#1b1b18] dark:text-[#EDEDEC]">Basic Information</h2>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Name</dt>
                            <dd class="mt-1 text-sm text-[#1b1b18] dark:text-[#EDEDEC]">{{ $topic->name }}</dd>
                        </div>
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Discipline</dt>
                            <dd class="mt-1 text-sm text-[#1b1b18] dark:text-[#EDEDEC]">
                                <a href="{{ route('disciplines.show', $topic->discipline) }}" class="text-blue-600 dark:text-blue-400 hover:underline">
                                    {{ $topic->discipline->name }}
                                </a>
                            </dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="bg-white dark:bg-[#161615] overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-xl font-semibold mb-4 text-[#1b1b18] dark:text-[#EDEDEC]">Statistics</h2>
                    <dl class="space-y-3">
                        <div>
                            <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">Chapters</dt>
                            <dd class="mt-1 text-sm text-[#1b1b18] dark:text-[#EDEDEC]">{{ $topic->chapters->count() }}</dd>
                        </div>
                    </dl>
                </div>
            </div>
        </div>

        @if($topic->chapters->isNotEmpty())
            <div class="mt-6 bg-white dark:bg-[#161615] overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h2 class="text-xl font-semibold mb-4 text-[#1b1b18] dark:text-[#EDEDEC]">Chapters</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        @foreach($topic->chapters as $chapter)
                            <div class="border border-gray-200 dark:border-gray-700 rounded p-4">
                                <h3 class="font-medium text-[#1b1b18] dark:text-[#EDEDEC]">{{ $chapter->name }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">{{ $chapter->subjects->count() }} subjects</p>
                                <a href="{{ route('chapters.show', $chapter) }}" class="text-sm text-blue-600 dark:text-blue-400 hover:underline mt-2 inline-block">View →</a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection

