
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

    @if ( count($tasks) == 0)
        <p>No tasklists found.</p>
    @else
    @php 
    // echo '<pre>';
    // print_r($tasklists);
    // die;
    @endphp
        <ul>
            @foreach ($tasks['items'] as $task)
           
            {{-- [kind] => tasks#task
            [id] => Vi1nOXZ3UWt1MUtaN0I0WA
            [etag] => "LTE1NjcwOTk1NjU"
            [title] => msdjbghjfdgb
            [updated] => 2024-02-13T13:11:24.000Z
            [selfLink] => https://www.googleapis.com/tasks/v1/lists/MTI5NzY2Nzc0MzE3ODczOTUxMjc6MDow/tasks/Vi1nOXZ3UWt1MUtaN0I0WA
            [position] => 00000000000000000000
            [status] => needsAction
            [links] => Array
                (
                ) --}}
                 <li>
                    <h2>{{ $task['title'] }}</h2>
                   <p>{{ $task['id'] }}</p> 
                   <a href="{{ url('edit-task/'.$task['id']) }}">Edit</a>
                   <a href="{{ url('delete-task/'.$task['id']) }}">Delete</a>
                   <a href="{{ url('view-task/'.$task['id']) }}">View</a> 
                   <p>{{ $task['position'] }}</p> 
                   <p>{{ $task['status'] }}</p> 
                    
                </li> 
            @endforeach
        </ul>
    @endif
</div>