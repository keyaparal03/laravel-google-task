<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TasklistController extends Controller
{
    public function updateTaskList(Request $request)
    {
        $taskListId = 'MTI5NzY2Nzc0MzE3ODczOTUxMjc6MDow'; // Replace with your actual task list ID

        $taskListData = [
            'title' => 'Your updated task list title',
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
        
        print_r($updatedTaskList);
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

    public function deleteTaskList(Request $request){
        $taskListId = 'OXZmOWpUWFllcmFON01KSQ'; // Replace with your actual task list ID
    
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
            echo "Task list deleted successfully";
        } else {
            echo "Failed to delete task list";
        }
    }
}
    
