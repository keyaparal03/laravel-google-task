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
        $access_token = Auth::user()->access_token;
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
