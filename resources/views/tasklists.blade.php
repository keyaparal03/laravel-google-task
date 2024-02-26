{{-- <link href="{{ mix('css/app.css') }}" rel="stylesheet">
<div class="container">
    <h1>Task List</h1>

    @if (session('successmsg'))
        <div class="alert alert-success">
            {{ session('successmsg') }}
        </div>
    @endif

    @if (session('errormsg'))
        <div class="alert alert-error">
            {{ session('errormsg') }}
        </div>
    @endif

    @if ( count($tasklists) == 0)
        <p>No tasklists found.</p>
    @else
    @php 
    // echo '<pre>';
    // print_r($tasklists);
    // die;
    @endphp
        <ul>
            @foreach ($tasklists['items'] as $task)
           

                 <li>
                   <h2>{{ $task['title'] }}</h2>
                   <p>{{ $task['id'] }}</p> 
                   <a href="{{ url('edit-tasklist/'.$task['id']) }}">Edit</a>
                   <a href="{{ url('delete-tasklist/'.$task['id']) }}">Delete</a>
                   <a href="{{ url('view-tasklist/'.$task['id']) }}">View</a>
                   <a href="{{ url('tasklist/'.$task['id']."/tasks") }}">Tasks</a>
                </li> 
            @endforeach
        </ul>
    @endif
</div> --}}

<x-app-layout>
    {{-- @livewireScripts --}}
    
    {{-- <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot> --}}

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg"> --}}
                {{-- <x-welcome /> --}}

                @livewire('sortable-item')
                
            {{-- </div> --}}
        </div>
    </div>
</x-app-layout>

