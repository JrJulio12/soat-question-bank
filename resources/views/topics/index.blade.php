@extends('layouts.app')

@section('title', 'Topics')

@section('content')
<div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-3xl font-bold text-[#1b1b18] dark:text-[#EDEDEC]">Topics</h1>
                <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">Manage all topics</p>
            </div>
            <a href="{{ route('topics.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">
                Create Topic
            </a>
        </div>

        @if(session('success'))
            <div class="mb-4 bg-green-50 dark:bg-green-900 border border-green-200 dark:border-green-700 text-green-800 dark:text-green-200 px-4 py-3 rounded">
                {{ session('success') }}
            </div>
        @endif

        <div class="mb-4">
            <form method="GET" action="{{ route('topics.index') }}" class="flex gap-2">
                <select name="discipline_id" class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-[#161615] dark:text-[#EDEDEC] shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="">All Disciplines</option>
                    @foreach($disciplines as $discipline)
                        <option value="{{ $discipline->id }}" {{ request('discipline_id') == $discipline->id ? 'selected' : '' }}>
                            {{ $discipline->name }}
                        </option>
                    @endforeach
                </select>
                <button type="submit" class="bg-gray-600 hover:bg-gray-700 text-white font-medium py-2 px-4 rounded-lg transition-colors">Filter</button>
            </form>
        </div>

        <div class="bg-white dark:bg-[#161615] overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6">
                @if($topics->isEmpty())
                    <p class="text-gray-500 dark:text-gray-400">No topics found.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-50 dark:bg-gray-800">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Name</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Discipline</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Chapters</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-[#161615] divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($topics as $topic)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-[#1b1b18] dark:text-[#EDEDEC]">
                                            {{ $topic->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $topic->discipline->name }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                                            {{ $topic->chapters->count() }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('topics.show', $topic) }}" class="text-blue-600 dark:text-blue-400 hover:text-blue-900 dark:hover:text-blue-300 mr-3">View</a>
                                            <a href="{{ route('topics.edit', $topic) }}" class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 mr-3">Edit</a>
                                            <form action="{{ route('topics.destroy', $topic) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this topic?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-red-600 dark:text-red-400 hover:text-red-900 dark:hover:text-red-300">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-4">
                        {{ $topics->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

