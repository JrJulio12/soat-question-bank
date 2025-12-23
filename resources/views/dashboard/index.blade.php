@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="py-8">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-4xl font-bold bg-gradient-to-r from-gray-900 to-gray-600 dark:from-gray-100 dark:to-gray-400 bg-clip-text text-transparent">
                        Dashboard
                    </h1>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">Overview of all application structure and statistics</p>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
            <!-- Disciplines Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-200 dark:border-gray-700 group">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-2">
                                <div class="p-2 bg-blue-100 dark:bg-blue-900/30 rounded-lg">
                                    <svg class="w-6 h-6 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">Disciplines</p>
                            </div>
                            <p class="text-4xl font-bold text-gray-900 dark:text-gray-100 mt-2">{{ $stats['disciplines'] }}</p>
                            <div class="flex items-center space-x-3 mt-3">
                                <span class="text-xs px-2 py-1 rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300 font-medium">
                                    EF: {{ $stats['disciplines_ef'] }}
                                </span>
                                <span class="text-xs px-2 py-1 rounded-full bg-indigo-100 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-300 font-medium">
                                    EM: {{ $stats['disciplines_em'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('disciplines.index') }}" class="mt-4 inline-flex items-center text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 group-hover:translate-x-1 transition-transform">
                        View all
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Units Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-200 dark:border-gray-700 group">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-2">
                                <div class="p-2 bg-green-100 dark:bg-green-900/30 rounded-lg">
                                    <svg class="w-6 h-6 text-green-600 dark:text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">Units</p>
                            </div>
                            <p class="text-4xl font-bold text-gray-900 dark:text-gray-100 mt-2">{{ $stats['units'] }}</p>
                        </div>
                    </div>
                    <a href="{{ route('units.index') }}" class="mt-4 inline-flex items-center text-sm font-medium text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300 group-hover:translate-x-1 transition-transform">
                        View all
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Knowledges Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-200 dark:border-gray-700 group">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-2">
                                <div class="p-2 bg-purple-100 dark:bg-purple-900/30 rounded-lg">
                                    <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"></path>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">Knowledges</p>
                            </div>
                            <p class="text-4xl font-bold text-gray-900 dark:text-gray-100 mt-2">{{ $stats['knowledges'] }}</p>
                        </div>
                    </div>
                    <a href="{{ route('knowledges.index') }}" class="mt-4 inline-flex items-center text-sm font-medium text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 group-hover:translate-x-1 transition-transform">
                        View all
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Topics Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-200 dark:border-gray-700 group">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-2">
                                <div class="p-2 bg-yellow-100 dark:bg-yellow-900/30 rounded-lg">
                                    <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">Topics</p>
                            </div>
                            <p class="text-4xl font-bold text-gray-900 dark:text-gray-100 mt-2">{{ $stats['topics'] }}</p>
                        </div>
                    </div>
                    <a href="{{ route('topics.index') }}" class="mt-4 inline-flex items-center text-sm font-medium text-yellow-600 dark:text-yellow-400 hover:text-yellow-700 dark:hover:text-yellow-300 group-hover:translate-x-1 transition-transform">
                        View all
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Chapters Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-200 dark:border-gray-700 group">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-2">
                                <div class="p-2 bg-indigo-100 dark:bg-indigo-900/30 rounded-lg">
                                    <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">Chapters</p>
                            </div>
                            <p class="text-4xl font-bold text-gray-900 dark:text-gray-100 mt-2">{{ $stats['chapters'] }}</p>
                        </div>
                    </div>
                    <a href="{{ route('chapters.index') }}" class="mt-4 inline-flex items-center text-sm font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300 group-hover:translate-x-1 transition-transform">
                        View all
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Subjects Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-200 dark:border-gray-700 group">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-2">
                                <div class="p-2 bg-pink-100 dark:bg-pink-900/30 rounded-lg">
                                    <svg class="w-6 h-6 text-pink-600 dark:text-pink-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">Subjects</p>
                            </div>
                            <p class="text-4xl font-bold text-gray-900 dark:text-gray-100 mt-2">{{ $stats['subjects'] }}</p>
                        </div>
                    </div>
                    <a href="{{ route('subjects.index') }}" class="mt-4 inline-flex items-center text-sm font-medium text-pink-600 dark:text-pink-400 hover:text-pink-700 dark:hover:text-pink-300 group-hover:translate-x-1 transition-transform">
                        View all
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Series Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-200 dark:border-gray-700 group">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-2">
                                <div class="p-2 bg-red-100 dark:bg-red-900/30 rounded-lg">
                                    <svg class="w-6 h-6 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">Series</p>
                            </div>
                            <p class="text-4xl font-bold text-gray-900 dark:text-gray-100 mt-2">{{ $stats['series'] }}</p>
                            <div class="flex items-center space-x-3 mt-3">
                                <span class="text-xs px-2 py-1 rounded-full bg-red-100 dark:bg-red-900/30 text-red-700 dark:text-red-300 font-medium">
                                    EF: {{ $stats['series_ef'] }}
                                </span>
                                <span class="text-xs px-2 py-1 rounded-full bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300 font-medium">
                                    EM: {{ $stats['series_em'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('series.index') }}" class="mt-4 inline-flex items-center text-sm font-medium text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300 group-hover:translate-x-1 transition-transform">
                        View all
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- BNCCs Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-200 dark:border-gray-700 group">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-2">
                                <div class="p-2 bg-teal-100 dark:bg-teal-900/30 rounded-lg">
                                    <svg class="w-6 h-6 text-teal-600 dark:text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">BNCCs</p>
                            </div>
                            <p class="text-4xl font-bold text-gray-900 dark:text-gray-100 mt-2">{{ $stats['bnccs'] }}</p>
                            <div class="flex items-center space-x-3 mt-3">
                                <span class="text-xs px-2 py-1 rounded-full bg-teal-100 dark:bg-teal-900/30 text-teal-700 dark:text-teal-300 font-medium">
                                    EF: {{ $stats['bnccs_ef'] }}
                                </span>
                                <span class="text-xs px-2 py-1 rounded-full bg-cyan-100 dark:bg-cyan-900/30 text-cyan-700 dark:text-cyan-300 font-medium">
                                    EM: {{ $stats['bnccs_em'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('bnccs.index') }}" class="mt-4 inline-flex items-center text-sm font-medium text-teal-600 dark:text-teal-400 hover:text-teal-700 dark:hover:text-teal-300 group-hover:translate-x-1 transition-transform">
                        View all
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>

            <!-- Questions Card -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden rounded-xl shadow-lg hover:shadow-xl transition-all duration-300 border border-gray-200 dark:border-gray-700 group">
                <div class="p-6">
                    <div class="flex items-center justify-between">
                        <div class="flex-1">
                            <div class="flex items-center space-x-2 mb-2">
                                <div class="p-2 bg-orange-100 dark:bg-orange-900/30 rounded-lg">
                                    <svg class="w-6 h-6 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <p class="text-sm font-semibold text-gray-600 dark:text-gray-400 uppercase tracking-wide">Questions</p>
                            </div>
                            <p class="text-4xl font-bold text-gray-900 dark:text-gray-100 mt-2">{{ $stats['questions'] }}</p>
                            <div class="flex items-center space-x-3 mt-3">
                                <span class="text-xs px-2 py-1 rounded-full bg-orange-100 dark:bg-orange-900/30 text-orange-700 dark:text-orange-300 font-medium">
                                    EF: {{ $stats['questions_ef'] }}
                                </span>
                                <span class="text-xs px-2 py-1 rounded-full bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-300 font-medium">
                                    EM: {{ $stats['questions_em'] }}
                                </span>
                            </div>
                        </div>
                    </div>
                    <a href="{{ route('questions.index') }}" class="mt-4 inline-flex items-center text-sm font-medium text-orange-600 dark:text-orange-400 hover:text-orange-700 dark:hover:text-orange-300 group-hover:translate-x-1 transition-transform">
                        View all
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <!-- Hierarchical Structure Sections -->
        <div class="space-y-6">
            <!-- Discipline → Unit → Knowledge Hierarchy -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 flex items-center space-x-2">
                            <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                            </svg>
                            <span>Discipline → Unit → Knowledge</span>
                        </h2>
                        <button onclick="toggleSection('units-hierarchy')" class="p-2 rounded-lg text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <svg id="units-hierarchy-icon" class="w-6 h-6 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                    </div>
                    <div id="units-hierarchy" class="hidden">
                        @if($disciplinesWithUnits->isEmpty())
                            <div class="text-center py-8">
                                <p class="text-gray-500 dark:text-gray-400">No disciplines found.</p>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($disciplinesWithUnits as $discipline)
                                    <div class="border-l-4 border-blue-500 pl-6 py-3 bg-blue-50/50 dark:bg-blue-900/10 rounded-r-lg">
                                        <div class="flex items-center justify-between">
                                            <h3 class="font-bold text-lg text-gray-900 dark:text-gray-100 flex items-center space-x-2">
                                                <span>{{ $discipline->name }}</span>
                                                @if($discipline->stage)
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300">
                                                        {{ $discipline->stage->value }}
                                                    </span>
                                                @endif
                                            </h3>
                                            <a href="{{ route('disciplines.show', $discipline) }}" class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 flex items-center space-x-1">
                                                <span>View</span>
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </a>
                                        </div>
                                        @if($discipline->units->isNotEmpty())
                                            <div class="mt-3 ml-4 space-y-3">
                                                @foreach($discipline->units as $unit)
                                                    <div class="border-l-4 border-green-500 pl-4 py-2 bg-green-50/50 dark:bg-green-900/10 rounded-r">
                                                        <div class="flex items-center justify-between">
                                                            <h4 class="font-semibold text-gray-800 dark:text-gray-200">{{ $unit->name }}</h4>
                                                            <a href="{{ route('units.show', $unit) }}" class="text-xs font-medium text-green-600 dark:text-green-400 hover:text-green-700 dark:hover:text-green-300">View</a>
                                                        </div>
                                                        @if($unit->knowledges->isNotEmpty())
                                                            <div class="mt-2 ml-4 space-y-1">
                                                                @foreach($unit->knowledges->take(3) as $knowledge)
                                                                    <div class="flex items-center justify-between text-sm py-1">
                                                                        <span class="text-gray-600 dark:text-gray-400 truncate">{{ Str::limit($knowledge->name, 60) }}</span>
                                                                        <a href="{{ route('knowledges.show', $knowledge) }}" class="text-xs text-purple-600 dark:text-purple-400 hover:text-purple-700 dark:hover:text-purple-300 ml-2 flex-shrink-0">View</a>
                                                                    </div>
                                                                @endforeach
                                                                @if($unit->knowledges->count() > 3)
                                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">+{{ $unit->knowledges->count() - 3 }} more</p>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Discipline → Topic → Chapter → Subject Hierarchy -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 flex items-center space-x-2">
                            <svg class="w-6 h-6 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span>Discipline → Topic → Chapter → Subject</span>
                        </h2>
                        <button onclick="toggleSection('topics-hierarchy')" class="p-2 rounded-lg text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <svg id="topics-hierarchy-icon" class="w-6 h-6 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                    </div>
                    <div id="topics-hierarchy" class="hidden">
                        @if($disciplinesWithUnits->isEmpty())
                            <div class="text-center py-8">
                                <p class="text-gray-500 dark:text-gray-400">No disciplines found.</p>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($disciplinesWithUnits as $discipline)
                                    @if($discipline->topics->isNotEmpty())
                                        <div class="border-l-4 border-blue-500 pl-6 py-3 bg-blue-50/50 dark:bg-blue-900/10 rounded-r-lg">
                                            <div class="flex items-center justify-between">
                                                <h3 class="font-bold text-lg text-gray-900 dark:text-gray-100 flex items-center space-x-2">
                                                    <span>{{ $discipline->name }}</span>
                                                    @if($discipline->stage)
                                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300">
                                                            {{ $discipline->stage->value }}
                                                        </span>
                                                    @endif
                                                </h3>
                                                <a href="{{ route('disciplines.show', $discipline) }}" class="text-sm font-medium text-blue-600 dark:text-blue-400 hover:text-blue-700 dark:hover:text-blue-300 flex items-center space-x-1">
                                                    <span>View</span>
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                    </svg>
                                                </a>
                                            </div>
                                            <div class="mt-3 ml-4 space-y-3">
                                                @foreach($discipline->topics as $topic)
                                                    <div class="border-l-4 border-yellow-500 pl-4 py-2 bg-yellow-50/50 dark:bg-yellow-900/10 rounded-r">
                                                        <div class="flex items-center justify-between">
                                                            <h4 class="font-semibold text-gray-800 dark:text-gray-200">{{ $topic->name }}</h4>
                                                            <a href="{{ route('topics.show', $topic) }}" class="text-xs font-medium text-yellow-600 dark:text-yellow-400 hover:text-yellow-700 dark:hover:text-yellow-300">View</a>
                                                        </div>
                                                        @if($topic->chapters->isNotEmpty())
                                                            <div class="mt-2 ml-4 space-y-2">
                                                                @foreach($topic->chapters->take(3) as $chapter)
                                                                    <div class="border-l-4 border-indigo-500 pl-3 py-1.5 bg-indigo-50/50 dark:bg-indigo-900/10 rounded-r">
                                                                        <div class="flex items-center justify-between">
                                                                            <h5 class="font-medium text-sm text-gray-700 dark:text-gray-300">{{ $chapter->name }}</h5>
                                                                            <a href="{{ route('chapters.show', $chapter) }}" class="text-xs font-medium text-indigo-600 dark:text-indigo-400 hover:text-indigo-700 dark:hover:text-indigo-300">View</a>
                                                                        </div>
                                                                        @if($chapter->subjects->isNotEmpty())
                                                                            <div class="mt-1.5 ml-3 space-y-1">
                                                                                @foreach($chapter->subjects->take(3) as $subject)
                                                                                    <div class="flex items-center justify-between text-xs py-0.5">
                                                                                        <span class="text-gray-600 dark:text-gray-400 truncate">{{ Str::limit($subject->name, 50) }}</span>
                                                                                        <a href="{{ route('subjects.show', $subject) }}" class="text-pink-600 dark:text-pink-400 hover:text-pink-700 dark:hover:text-pink-300 ml-2 flex-shrink-0">View</a>
                                                                                    </div>
                                                                                @endforeach
                                                                                @if($chapter->subjects->count() > 3)
                                                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">+{{ $chapter->subjects->count() - 3 }} more</p>
                                                                                @endif
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                @endforeach
                                                                @if($topic->chapters->count() > 3)
                                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">+{{ $topic->chapters->count() - 3 }} more chapters</p>
                                                                @endif
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Series & BNCCs -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 flex items-center space-x-2">
                            <svg class="w-6 h-6 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                            <span>Series & BNCCs</span>
                        </h2>
                        <button onclick="toggleSection('bnccs-section')" class="p-2 rounded-lg text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <svg id="bnccs-section-icon" class="w-6 h-6 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                    </div>
                    <div id="bnccs-section" class="hidden">
                        <div class="space-y-6">
                            <div>
                                <h3 class="font-bold text-lg mb-4 text-gray-900 dark:text-gray-100 flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                                    </svg>
                                    <span>Series</span>
                                </h3>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                    @foreach($series->take(9) as $serie)
                                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:border-red-300 dark:hover:border-red-700 hover:shadow-md transition-all bg-gray-50/50 dark:bg-gray-700/30">
                                            <div class="flex items-center justify-between mb-2">
                                                <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $serie->name }}</span>
                                                <a href="{{ route('series.show', $serie) }}" class="text-xs font-medium text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300">View</a>
                                            </div>
                                            <p class="text-xs text-gray-600 dark:text-gray-400">
                                                {{ $serie->bnccs->count() }} BNCC{{ $serie->bnccs->count() !== 1 ? 's' : '' }}
                                            </p>
                                        </div>
                                    @endforeach
                                </div>
                                @if($series->count() > 9)
                                    <a href="{{ route('series.index') }}" class="mt-4 inline-flex items-center text-sm font-medium text-red-600 dark:text-red-400 hover:text-red-700 dark:hover:text-red-300">
                                        View all {{ $series->count() }} series
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                @endif
                            </div>
                            <div>
                                <h3 class="font-bold text-lg mb-4 text-gray-900 dark:text-gray-100 flex items-center space-x-2">
                                    <svg class="w-5 h-5 text-teal-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                    </svg>
                                    <span>BNCCs</span>
                                </h3>
                                <div class="space-y-3">
                                    @foreach($bnccs->take(10) as $bncc)
                                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:border-teal-300 dark:hover:border-teal-700 hover:shadow-md transition-all bg-gray-50/50 dark:bg-gray-700/30">
                                            <div class="flex items-center justify-between mb-2">
                                                <div class="flex items-center space-x-2">
                                                    <span class="font-semibold text-gray-900 dark:text-gray-100">{{ $bncc->code }}</span>
                                                    <span class="px-2 py-0.5 text-xs font-medium rounded-full bg-teal-100 dark:bg-teal-900/30 text-teal-700 dark:text-teal-300">
                                                        {{ $bncc->stage->value }}
                                                    </span>
                                                </div>
                                                <a href="{{ route('bnccs.show', $bncc) }}" class="text-xs font-medium text-teal-600 dark:text-teal-400 hover:text-teal-700 dark:hover:text-teal-300">View</a>
                                            </div>
                                            @if($bncc->description)
                                                <p class="text-xs text-gray-600 dark:text-gray-400 line-clamp-2">{{ $bncc->description }}</p>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                                @if($bnccs->count() > 10)
                                    <a href="{{ route('bnccs.index') }}" class="mt-4 inline-flex items-center text-sm font-medium text-teal-600 dark:text-teal-400 hover:text-teal-700 dark:hover:text-teal-300">
                                        View all {{ $bnccs->count() }} BNCCs
                                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                        </svg>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Questions -->
            <div class="bg-white dark:bg-gray-800 overflow-hidden rounded-xl shadow-lg border border-gray-200 dark:border-gray-700">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 flex items-center space-x-2">
                            <svg class="w-6 h-6 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>Recent Questions</span>
                        </h2>
                        <button onclick="toggleSection('questions-section')" class="p-2 rounded-lg text-gray-500 hover:text-gray-700 dark:hover:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                            <svg id="questions-section-icon" class="w-6 h-6 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>
                    </div>
                    <div id="questions-section" class="hidden">
                        @if($questions->isEmpty())
                            <div class="text-center py-8">
                                <p class="text-gray-500 dark:text-gray-400">No questions found.</p>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($questions as $question)
                                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-5 hover:border-orange-300 dark:hover:border-orange-700 hover:shadow-md transition-all bg-gray-50/50 dark:bg-gray-700/30">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2 mb-3 flex-wrap">
                                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-300">
                                                        {{ $question->stage->value }}
                                                    </span>
                                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-purple-100 dark:bg-purple-900/30 text-purple-700 dark:text-purple-300">
                                                        {{ $question->type->value }}
                                                    </span>
                                                    <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $question->status->value === 'published' ? 'bg-green-100 dark:bg-green-900/30 text-green-700 dark:text-green-300' : 'bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300' }}">
                                                        {{ $question->status->value }}
                                                    </span>
                                                </div>
                                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100 line-clamp-2 mb-3">{{ Str::limit($question->stem, 100) }}</p>
                                                <div class="flex gap-4 text-xs text-gray-600 dark:text-gray-400">
                                                    <span class="flex items-center space-x-1">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                                                        </svg>
                                                        <span>{{ $question->bnccs->count() }} BNCC{{ $question->bnccs->count() !== 1 ? 's' : '' }}</span>
                                                    </span>
                                                    <span class="flex items-center space-x-1">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                                                        </svg>
                                                        <span>{{ $question->subjects->count() }} Subject{{ $question->subjects->count() !== 1 ? 's' : '' }}</span>
                                                    </span>
                                                </div>
                                            </div>
                                            <a href="{{ route('questions.show', $question) }}" class="ml-4 text-sm font-medium text-orange-600 dark:text-orange-400 hover:text-orange-700 dark:hover:text-orange-300 flex items-center space-x-1 flex-shrink-0">
                                                <span>View</span>
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <a href="{{ route('questions.index') }}" class="mt-6 inline-flex items-center text-sm font-medium text-orange-600 dark:text-orange-400 hover:text-orange-700 dark:hover:text-orange-300">
                                View all questions
                                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function toggleSection(sectionId) {
    const section = document.getElementById(sectionId);
    const icon = document.getElementById(sectionId + '-icon');
    
    if (section.classList.contains('hidden')) {
        section.classList.remove('hidden');
        icon.classList.add('rotate-180');
    } else {
        section.classList.add('hidden');
        icon.classList.remove('rotate-180');
    }
}
</script>
@endsection

