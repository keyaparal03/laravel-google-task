<?php

namespace App\Http\Controllers;

use App\Contracts\TaskListInterface;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;

class TasklistController extends Controller implements TaskListInterface
{
    public function add()
    {
        return view('add-tasklist');
    }
    function save(Request $request)
    {
        $validatedData = $request->validate([
            'task_name' => 'required'
        ]);
        $taskData = [
            'title' => $request->task_name,
            // Add other task properties as needed
        ];

        $access_token = getAccessToken();

        $server_output = guzzle_post(
            "https://tasks.googleapis.com/tasks/v1/users/@me/lists",
            $taskData,
            ['Accept' => 'application/json', 'Authorization' => 'Bearer ' . $access_token]
        );

        echo '<pre>';
        print_r($server_output);
        echo '</pre>';
    }
    function edit($id)
    {
        try{
            $taskListId = $id; // Replace with your actual task list ID

            $accessToken = getAccessToken(); // Replace with your actual access token
    
            $client = new \GuzzleHttp\Client();
    
            $response = $client->get(
                "https://tasks.googleapis.com/tasks/v1/users/@me/lists/{$taskListId}",
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $accessToken,
                    ],
                ]
            );
    
            $taskData = json_decode($response->getBody()->getContents(), true);
             
            return view('edit-tasklist',compact('taskData'));
        } 
        catch(Exception $e) {
            echo $e->getMessage();
            exit();
        }
        
    }
    function update(Request $request)
    {
       
        try{
            $validatedData = $request->validate([
                'task_name' => 'required'
            ]);
            $taskListId = $request->id; // Replace with your actual task list ID

            $taskListData = [
                'title' => $request->task_name,
                // Add other task list properties as needed
            ];
            
            $accessToken = getAccessToken();
            
            $client = new \GuzzleHttp\Client();
            
            $response = $client->patch(
                "https://tasks.googleapis.com/tasks/v1/users/@me/lists/{$taskListId}",
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $accessToken,
                        'Content-Type' => 'application/json',
                    ],
                    'json' => $taskListData,
                ]
            );
            
            $updatedTaskList = json_decode($response->getBody()->getContents(), true);
            
            if ($response->getStatusCode() == 200) {
                return redirect()->route('tasklists')->with('successmsg', 'Task List updated successfully');
            } else {
                return redirect()->route('tasklists')->with('errormsg', 'Failed to update task list');
            }
        } 
        catch(Exception $e) {
            echo $e->getMessage();
            exit();
        }
    }
    function delete($id)
    {
        try{
            $taskListId = $id; // Replace with your actual task list ID
        
            $accessToken = getAccessToken(); // Replace with your actual access token
        
            $client = new \GuzzleHttp\Client();
        
            $response = $client->delete(
                "https://tasks.googleapis.com/tasks/v1/users/@me/lists/{$taskListId}",
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $accessToken,
                    ],
                ]
            );
        
            if ($response->getStatusCode() == 204) {
                return redirect()->route('tasklists')->with('successmsg', 'Task List deleted successfully');
            } else {
                return redirect()->route('tasklists')->with('errormsg', 'Failed to delete task list');
            }
        } 
        catch(Exception $e) {
            echo $e->getMessage();
            exit();
        }
    }
    function lists()
    {
    
        try{
            $access_token = getAccessToken();
            //
            $tasklists = guzzle_get('https://tasks.googleapis.com/tasks/v1/users/@me/lists', ['Accept' => 'application/json', 'Authorization' => 'Bearer ' . $access_token]);

        
            return view('tasklists',compact('tasklists'));
        } 
        catch(Exception $e) {
            echo $e->getMessage();
            exit();
        }
    }
    function view($id)
    {

        try{
            $taskListId = $id; // Replace with your actual task list ID

            $accessToken = getAccessToken(); // Replace with your actual access token
    
            $client = new \GuzzleHttp\Client();
    
            $response = $client->get(
                "https://tasks.googleapis.com/tasks/v1/users/@me/lists/{$taskListId}",
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $accessToken,
                    ],
                ]
            );
    
            $taskData = json_decode($response->getBody()->getContents(), true);
             
            dd($taskData);
        } 
        catch(Exception $e) {
            echo $e->getMessage();
            exit();
        }
        
    }
    function tasks($id)
    {
        $accessToken = getAccessToken(); 
        $tasks = guzzle_get('https://tasks.googleapis.com/tasks/v1/lists/'.$id.'/tasks', ['Accept' => 'application/json', 'Authorization' => 'Bearer ' . $accessToken]);
        return view('tasks',compact('tasks'));
    }
   







    
    public function completedTaskList(Request $request){
        $taskListId = 'OXZmOWpUWFllcmFON01KSQ'; // Replace with your actual task list ID
        $accessToken = getAccessToken(); // Replace with your actual access token
        $client = new \GuzzleHttp\Client();
        $response = $client->get(
            "https://tasks.googleapis.com/tasks/v1/lists/{$taskListId}/tasks?showCompleted=true",
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                ],
            ]
        );
        
        $tasks = json_decode($response->getBody()->getContents(), true)['items'];
        
        echo '<pre>';
        print_r($tasks); // Print all tasks
        
        $completedTasks = array_filter($tasks, function ($task) {
            return isset($task['status']) && $task['status'] == 'completed';
        });
        
        print_r($completedTasks); // Print completed tasks

        $starredTasks = array_filter($tasks, function ($task) {
            return isset($task['notes']) && $task['notes'] == 'STARRED';
        });
        
        print_r($starredTasks); // Print completed tasks

        $client = new \GuzzleHttp\Client();

        // Get the tasks
        $response = $client->get(
            "https://tasks.googleapis.com/tasks/v1/lists/{$taskListId}/tasks",
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                ],
            ]
        );

        $tasks = json_decode($response->getBody()->getContents(), true)['items'];

        // Sort the tasks by due date
        usort($tasks, function ($task1, $task2) {
            $due1 = isset($task1['due']) ? strtotime($task1['due']) : 0;
            $due2 = isset($task2['due']) ? strtotime($task2['due']) : 0;

            return $due1 - $due2;
        });

        print_r($tasks); 
       
    }
    public function starredTaskList(Request $request){
        $taskId = 'STNWck9lcEpET0w3UExlMA'; // Replace with your actual task ID
        $taskListId = 'OWF0SXdWTERKNHZxSTUzbQ'; // Replace with your actual task list ID
        $accessToken = getAccessToken(); // Replace with your actual access token

        $client = new \GuzzleHttp\Client();

        // Get the task
        $response = $client->get(
            "https://tasks.googleapis.com/tasks/v1/lists/{$taskListId}/tasks/{$taskId}",
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                ],
            ]
        );

        $task = json_decode($response->getBody()->getContents(), true);

        // Add "STARRED" to the notes field
        $task['notes'] = isset($task['notes']) ? 'starred' : 'starred';

        // Update the task
        $response = $client->put(
            "https://tasks.googleapis.com/tasks/v1/lists/{$taskListId}/tasks/{$taskId}",
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => $task,
            ]
        );

        $updatedTask = json_decode($response->getBody()->getContents(), true);

        print_r($updatedTask);
    }
    public function addDueDateTaskList(Request $request){
        $taskId = 'c3pZUVg3WENlak9EVjdIeQ'; // Replace with your actual task ID
        $taskListId = 'OXZmOWpUWFllcmFON01KSQ'; // Replace with your actual task list ID
        $accessToken = getAccessToken(); // Replace with your actual access token


        $dueDate = new DateTime('2024-02-15'); 
        $client = new \GuzzleHttp\Client();

        // Get the task
        $response = $client->get(
            "https://tasks.googleapis.com/tasks/v1/lists/{$taskListId}/tasks/{$taskId}",
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                ],
            ]
        );

        $task = json_decode($response->getBody()->getContents(), true);

        // Add the due date to the task
        $task['due'] = $dueDate->format(DateTime::RFC3339);

        // Update the task
            $response = $client->put(
            "https://tasks.googleapis.com/tasks/v1/lists/{$taskListId}/tasks/{$taskId}",
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => $task,
            ]
        );

        $updatedTask = json_decode($response->getBody()->getContents(), true);

        print_r($updatedTask);
    }

}
    
