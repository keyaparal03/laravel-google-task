<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class TaskController extends Controller
{
    // In YourController
    public function addTaskList(Request $request)
    {
       
        $validatedData = $request->validate([
            'task_name' => 'required'
        ]);

        echo $request->task_name;

        
        $taskListId = 'your-task-list-id'; // Replace with your actual task list ID
        $taskData = [
            'title' => 'Your task title',
            // Add other task properties as needed
        ];

        $access_token = Auth::user()->access_token;

        $server_output = guzzle_post(
            "https://tasks.googleapis.com/tasks/v1/users/@me/lists",
            $taskData,
            ['Accept' => 'application/json', 'Authorization' => 'Bearer ' . $access_token]
        );

        echo '<pre>';
        print_r($server_output);
        echo '</pre>';
        // Handle the validated data...

       // return redirect()->route('successPage');
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $access_token = Auth::user()->access_token;
        //
        $server_output = guzzle_get('https://tasks.googleapis.com/tasks/v1/users/@me/lists', ['Accept' => 'application/json', 'Authorization' => 'Bearer ' . $access_token]);

        //  print_r($server_output);
           
        //     $server_output = json_decode($server_output, true);
            echo '<pre>';
            print_r($server_output);
            echo '</pre>';

        return view('task-list');
    }
    public function tasklist($tasklistid){
       echo $access_token = Auth::user()->access_token;
        //
        $server_output = guzzle_get('https://www.googleapis.com/tasks/v1/users/@me/lists/'.$tasklistid, ['Accept' => 'application/json', 'Authorization' => 'Bearer ' . $access_token]);

        //  print_r($server_output);
           
        //     $server_output = json_decode($server_output, true);
            echo '<pre>';
            print_r($server_output);
            echo '</pre>';

           

            $server_output = guzzle_get('https://tasks.googleapis.com/tasks/v1/lists/'.$tasklistid.'/tasks', ['Accept' => 'application/json', 'Authorization' => 'Bearer ' . $access_token]);

            //  print_r($server_output);
               
            //     $server_output = json_decode($server_output, true);
                echo '<pre>';
                print_r($server_output);
                echo '</pre>';
       
    }
    
    /**
     * Show the form for creating a new resource.
     */
    public function add_task()
    {
        //
        return view('add-task');
    }

    public function addTaskToList()
    {
        //
        return view('add-task-to-list');
    }

    public function saveTaskToList(Request $request)
    {
        //dd($request);
        //    "task_id" => "MTI5NzY2Nzc0MzE3ODczOTUxMjc6MDow"
        //   "task_name" => "hgfhg"
        // $validatedData = $request->validate([
        //     'task_name' => 'required'
        // ]);

        $task_id = $request->task_id;
        $task_name = $request->task_name;

        
        $taskListId = 'your-task-list-id'; // Replace with your actual task list ID
        $taskData = [
            'title' => $task_name,
            // Add other task properties as needed
        ];

        $access_token = Auth::user()->access_token;

        $server_output = guzzle_post(
            "https://tasks.googleapis.com/tasks/v1/lists/".$task_id."/tasks",
            $taskData,
            ['Accept' => 'application/json', 'Authorization' => 'Bearer ' . $access_token]
        );

        echo '<pre>';
        print_r($server_output);
        echo '</pre>';



        $server_output1 = guzzle_post(
            "https://tasks.googleapis.com/tasks/v1/lists/".$task_id."/tasks",
            $server_output,
            ['Accept' => 'application/json', 'Authorization' => 'Bearer ' . $access_token]
        );

        echo '<pre>';
        print_r($server_output1);
        echo '</pre>';


        $server_output2 = guzzle_post(
            "https://tasks.googleapis.com/tasks/v1/lists/WXN3V1c1UVV1bWVRWnpzSQ/tasks",
            $server_output,
            ['Accept' => 'application/json', 'Authorization' => 'Bearer ' . $access_token]
        );

        echo '<pre>';
        print_r($server_output2);
        echo '</pre>';
        // Handle the validated data...

       // return redirect()->route('successPage');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    
    public function update_task()
    {
        //
        return view('update-task');
    }
    /**
     * Show the form for editing the specified resource.
     */
    public function updateTask(Request $request)
    {
       
        $taskListId = $request->tasklistid; // Replace with your actual task list ID
        $taskId = $request->taskid;// Replace with your actual task ID

        $taskData = [
            'title' => $request->task_name,
            "id" => $taskId,
            // Add other task properties as needed
        ];
    
        $accessToken = Auth::user()->access_token; // Replace with your actual access token
        $url = "https://tasks.googleapis.com/tasks/v1/lists/$taskListId/tasks/$taskId"; 

        $client = new \GuzzleHttp\Client();

        $response = $client->put($url, [
            'headers' => [
                'Authorization' => 'Bearer '. $accessToken,
            ],
            'json' => $taskData,
        ]);

        $updatedTask = json_decode($response->getBody()->getContents(), true);
    
        print_r($updatedTask);
    }
    public function movetask(Request $request)
    {
         $taskListId = $request->tasklistid; // Replace with your actual task list ID
        $taskId = $request->taskid;// Replace with your actual task ID

        $moveData = [
            'parent' => 'parent-task-id', // Optional. The ID of the new parent task
            'previous' => 'previous-task-id', // Optional. The ID of the previous sibling task
        ];

        $accessToken  = Auth::user()->access_token;// Replace with your actual access token

        $client = new \GuzzleHttp\Client();

        $response = $client->post(
            "https://tasks.googleapis.com/tasks/v1/lists/{$taskListId}/tasks/{$taskId}/move",
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => $moveData,
            ]
        );

        $movedTask = json_decode($response->getBody()->getContents(), true);

        print_r($movedTask);
    }
    public function deleteTask(Request $request){

        $taskListId = 'MTI5NzY2Nzc0MzE3ODczOTUxMjc6MDow'; // Replace with your actual task list ID
$taskId = 'bjNwek1yNmxsTUdqMkxCcQ'; // Replace with your actual task ID

$accessToken = Auth::user()->access_token; // Replace with your actual access token

$client = new \GuzzleHttp\Client();

$response = $client->delete(
    "https://tasks.googleapis.com/tasks/v1/lists/{$taskListId}/tasks/{$taskId}",
    [
        'headers' => [
            'Authorization' => 'Bearer ' . $accessToken,
        ],
    ]
);

if ($response->getStatusCode() == 204) {
    echo "Task deleted successfully";
} else {
    echo "Failed to delete task";
}

    }
}
