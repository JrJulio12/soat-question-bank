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
                <question-form 
                    :disciplines="{{ json_encode($disciplines) }}"
                    :units="{{ json_encode($units) }}"
                    :knowledges="{{ json_encode($knowledges) }}"
                    :bnccs="{{ json_encode($bnccs) }}"
                    :topics="{{ json_encode($topics) }}"
                    :chapters="{{ json_encode($chapters) }}"
                    :subjects="{{ json_encode($subjects) }}"
                    cancel-url="{{ route('questions.index') }}"
                ></question-form>
            </div>
        </div>
    </div>
</div>
@endsection
