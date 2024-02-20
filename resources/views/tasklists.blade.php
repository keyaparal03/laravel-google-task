
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
</div>