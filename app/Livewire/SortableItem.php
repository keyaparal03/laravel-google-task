<?php

namespace App\Livewire;

use Livewire\Component;
use App\Http\Controllers\TasklistController;
use DateTime;
use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;


class SortableItem extends Component
{
    public $showDiv = 0;
    public $showDivId = '';
    public $movingTaskid;
    public $movingTasklist;
    public $currenttasklist;
    public $listarray = [];
    public $task_name;
    public $showForm = true;
    public $addlisttitle;
    public $addlistdescription;

    protected $rules = [
        'task_name' => 'required',
    ];
    public $taskListData = array();

    public $inputValue;
    public $inputs = [];
    public $inputsTasktitle= [];
    public $inputsTaskNotes= [];
    public $inputsTaskDueDate= [];
    public $inputsTaskDueDateFormatted= [];
    
    public $modalData;

    protected $listeners = ['openModal' => 'openModal'];

    public function openModal($data)
    {
        $this->modalData = $data;
        $this->dispatchBrowserEvent('openModal');
    }
    public function submit()
    {
        // Your form submission logic...
    }
    public function mount()
    {
        $tasklistcontroller = new TasklistController();
        $tasklists =  $tasklistcontroller->lists();
    
      
       

        if(count($tasklists['tasklists']['items'])>0) : 
        
            foreach($tasklists['tasklists']['items'] as $tasklist)
            { 
                $tasks =  $tasklistcontroller->tasks($tasklist['id']);
                $tasks  = $tasks['tasks']['items'];

                $completedTasks =  $tasklistcontroller->completedTasks($tasklist['id']);
                $completedTasks  = $completedTasks['tasks']['items'];

                
                usort($tasks, function ($item1, $item2) {
                   
                    return $item1['position'] <=> $item2['position'];
                });
                foreach($tasks as $task)
                {
                    //dd($task);
                    $this->inputsTasktitle[$task['id']] = $task['title'] ?? '';
                    $this->inputsTaskNotes[$task['id']] = $task['notes'] ?? '';
                    if (isset($task['due'])) {
                        $dueDate = $task['due'] ?? '';
                        $date = Carbon::parse( $dueDate );
                        $formattedDate = $date->format('Y-m-d');

                        $formattedtoDisply = $date->format('D, M j');

                        $this->inputsTaskDueDate[$task['id']] = $formattedDate;
                        $this->inputsTaskDueDateFormatted[$task['id']] = $formattedtoDisply;
                    } else {
                        $this->inputsTaskDueDate[$task['id']] ='';
                        $this->inputsTaskDueDateFormatted[$task['id']] = '';
                    }
                    

                   
                   

                }
                //print_r($tasklist);
                $this->listarray["title_".$tasklist['id']]  = $tasklist['title'];
                $this->inputs[$tasklist['id']] = $tasklist['title'];
                //dd($this->listarray);
                $this->taskListData[$tasklist['id']]['tasklist'] = $tasklist;
                $this->taskListData[$tasklist['id']]['tasks'] = $tasks;
                $this->taskListData[$tasklist['id']]['completedTasks'] = $completedTasks;
            }

        endif;
        // $this->currenttasklist = $value['tasklist']['id'];
    }
    public function render()
    {
        try{

          

            return view('livewire.sortable-item');
            
        } 
        catch(Exception $e) {
            echo $e->getMessage();
            exit();
        }
       
    }
   
  
   
    public function updateTaskOrder($data)
    {
       
        foreach ($data as $order => $item) {

            if($item['value']==$this->movingTasklist)
            {
                $access_token = getAccessToken();
                foreach($item['items'] as $key => $task)
                {
                    
                    if($task['value'] == $this->movingTaskid)
                    { 
                        $client = new \GuzzleHttp\Client();
                        if($key == 0)
                        {
                            $response = $client->post(
                                "https://tasks.googleapis.com/tasks/v1/lists/{$this->movingTasklist}/tasks/{$this->movingTaskid}/move",
                                [
                                    'headers' => [
                                        'Authorization' => 'Bearer ' . $access_token,
                                    ],
                                ]
                            );
                        }
                        else{
                            // echo "movingTaskid = ".$this->movingTaskid;
                            // echo '<br>';
                            // echo "else";
                            // echo '<br>';
                            // echo "key = ".$key;
                            // echo '<br>';
                            // echo "peviouskey = ".$peviouskey = ($key==0) ? 0 : $key-1;
                            // echo '<br>';
                            // echo "value = ".$item['items'][$peviouskey]['value'];
                            // echo '<br>';
                            $peviouskey = ($key==0) ? 0 : $key-1;
                            $response = $client->post(
                                "https://tasks.googleapis.com/tasks/v1/lists/{$this->movingTasklist}/tasks/{$this->movingTaskid}/move?previous={$item['items'][$peviouskey]['value']}",
                                [
                                    'headers' => [
                                        'Authorization' => 'Bearer ' . $access_token,
                                    ],
                                ]
                            );
                            $movedTask = json_decode($response->getBody()->getContents(), true);
    
                            // print_r($movedTask);
                        }
                    }
                }
            // die;
            }
            else{
                foreach($item['items'] as $key => $task)
                {
                
                    if($task['value'] == $this->movingTaskid)
                    {
                        $oldpossition = ($key==0) ? 0 : $key-1;
                        
                        $newTaskListid = $item['value'];

                        $access_token = getAccessToken();

                        $server_output = guzzle_get("https://tasks.googleapis.com/tasks/v1/lists/".$this->movingTasklist."/tasks/".$this->movingTaskid, ['Accept' => 'application/json', 'Authorization' => 'Bearer ' . $access_token]);
                
                
                    
                
                        $server_output2 = guzzle_post(
                            "https://tasks.googleapis.com/tasks/v1/lists/".$newTaskListid."/tasks",
                            $server_output,
                            ['Accept' => 'application/json', 'Authorization' => 'Bearer ' . $access_token]
                        );
                        // dd( $server_output2);
                        $client = new \GuzzleHttp\Client();

                        $response = $client->delete('https://tasks.googleapis.com/tasks/v1/lists/' . $this->movingTasklist . '/tasks/' . $this->movingTaskid, [
                            'headers' => [
                                'Authorization' => 'Bearer ' . $access_token,
                                'Accept' => 'application/json'
                            ]
                        ]);

                    
                        if($item['items'][$oldpossition]['value']==$item['items'][$key]['value'])
                        {
                        //    echo "if";
                    
                            $response = $client->post(
                                "https://tasks.googleapis.com/tasks/v1/lists/".$newTaskListid."/tasks/".$server_output2['id']."/move",
                                [
                                    'headers' => [
                                        'Authorization' => 'Bearer ' . $access_token,
                                    ],
                                ]
                            );
                        
                        }else{
                            // echo "else";
                            $response = $client->post(
                                "https://tasks.googleapis.com/tasks/v1/lists/{$newTaskListid}/tasks/{$server_output2['id']}/move?previous={$item['items'][$oldpossition]['value']}",
                                [
                                    'headers' => [
                                        'Authorization' => 'Bearer ' . $access_token,
                                    ],
                                ]
                            );
                        
                        }
                    }
                }
            }
        }
        // die;
        
        $tasklistcontroller = new TasklistController();
            $tasklists =  $tasklistcontroller->lists();
            $taskListData = array();
            if(count($tasklists['tasklists']['items'])>0) : 
            
                foreach($tasklists['tasklists']['items'] as $tasklist)
                { 
                    $tasks =  $tasklistcontroller->tasks($tasklist['id']);
                    $tasks  = $tasks['tasks']['items'];
                    usort($tasks, function ($item1, $item2) {
                        return $item1['position'] <=> $item2['position'];
                    });
                    $this->taskListData[$tasklist['id']]['tasklist'] = $tasklist;
                    $this->taskListData[$tasklist['id']]['tasks'] = $tasks;
                }
            endif;

            // dd($taskListData);
            // $this->emit('refreshComponent');
            return view('livewire.sortable-item');

            // return redirect()->to('/tasklists');
        
        // die;
    }
    public function updateGroupOrder($data)
    {
        // echo '<pre>';
        // dd($data);
    }
    public function addTaskList()
    {
        
        $data = array('title' => $this->addlisttitle, 'description' => $this->addlistdescription);
       
        $tasklistcontroller = new TasklistController();
        $tasklistcontroller->save($data);
        return redirect()->to('/tasklists');
        // $tasklists =  $tasklistcontroller->lists();
        //     // $taskListData = array();
        //     if(count($tasklists['tasklists']['items'])>0) : 
            
        //         foreach($tasklists['tasklists']['items'] as $tasklist)
        //         { 
        //             $tasks =  $tasklistcontroller->tasks($tasklist['id']);
        //             $tasks  = $tasks['tasks']['items'];
        //             usort($tasks, function ($item1, $item2) {
        //                 return $item1['position'] <=> $item2['position'];
        //             });
        //             $this->taskListData[$tasklist['id']]['tasklist'] = $tasklist;
        //             $this->taskListData[$tasklist['id']]['tasks'] = $tasks;
        //         }
        //     endif;
    }
    public function updatetaskListData()
    {
        $tasklistcontroller = new TasklistController();
        $tasklists =  $tasklistcontroller->lists();
        $taskListData = array();
        if(count($tasklists['tasklists']['items'])>0) : 
        
            foreach($tasklists['tasklists']['items'] as $tasklist)
            { 
                $tasks =  $tasklistcontroller->tasks($tasklist['id']);
                $tasks  = $tasks['tasks']['items'];
                usort($tasks, function ($item1, $item2) {
                    return $item1['position'] <=> $item2['position'];
                });
                $this->taskListData[$tasklist['id']]['tasklist'] = $tasklist;
                $this->taskListData[$tasklist['id']]['tasks'] = $tasks;
            }
        endif;
    }
//     public function update($field, $value)
// {
//     // Access the value of a variable
//     $tasklistcontroller = new TasklistController();
//     $tasklists =  $tasklistcontroller->lists();
//     $taskListData = array();
//     if(count($tasklists['tasklists']['items'])>0) : 
    
//         foreach($tasklists['tasklists']['items'] as $tasklist)
//         { 
//             $tasks =  $tasklistcontroller->tasks($tasklist['id']);
//             $tasks  = $tasks['tasks']['items'];
//             usort($tasks, function ($item1, $item2) {
//                 return $item1['position'] <=> $item2['position'];
//             });
//             $this->taskListData[$tasklist['id']]['tasklist'] = $tasklist;
//             $this->taskListData[$tasklist['id']]['tasks'] = $tasks;
//         }
//     endif;

//     // Your custom logic here...

//     // Call the parent update method to maintain the default behavior
//     parent::update($field, $value);
// }

    public function editTaskList($id)
    {
        if($this->inputs[$id]!='')
        {
            $data = array('id' => $id, 'title' => $this->inputs[$id]);
            $tasklistcontroller = new TasklistController();
            $status =  $tasklistcontroller->update($data);
        }
        
            $tasklists =  $tasklistcontroller->lists();
            $taskListData = array();
            if(count($tasklists['tasklists']['items'])>0) : 
            
                foreach($tasklists['tasklists']['items'] as $tasklist)
                { 
                    $tasks =  $tasklistcontroller->tasks($tasklist['id']);
                    $tasks  = $tasks['tasks']['items'];
                    usort($tasks, function ($item1, $item2) {
                        return $item1['position'] <=> $item2['position'];
                    });
                    $this->taskListData[$tasklist['id']]['tasklist'] = $tasklist;
                    $this->taskListData[$tasklist['id']]['tasks'] = $tasks;
                }
            endif;
        return view('livewire.sortable-item');
    }
    public function removeGroup($id)
    {
        $tasklistcontroller = new TasklistController();
        $status =  $tasklistcontroller->delete($id);
        $tasklists =  $tasklistcontroller->lists();
        // $taskListData = array();
        // if(count($tasklists['tasklists']['items'])>0) : 
        
        //     foreach($tasklists['tasklists']['items'] as $tasklist)
        //     { 
        //         $tasks =  $tasklistcontroller->tasks($tasklist['id']);
        //         $tasks  = $tasks['tasks']['items'];
        //         usort($tasks, function ($item1, $item2) {
        //             return $item1['position'] <=> $item2['position'];
        //         });
        //         $this->taskListData[$tasklist['id']]['tasklist'] = $tasklist;
        //         $this->taskListData[$tasklist['id']]['tasks'] = $tasks;
        //     }
        // endif;
        // return view('livewire.sortable-item');

        return redirect()->to('/tasklists');
        
    }
    public function editTaskDueDate($taskListId,$taskId)
    {
       
        if($this->inputsTaskDueDate[$taskId]!=''){
            $dueDate = new DateTime($this->inputsTaskDueDate[$taskId]); 
        }
        $taskData = [
            'title' => $this->inputsTasktitle[$taskId],
            'notes' => $this->inputsTaskNotes[$taskId],
            'due' => ($dueDate->format(DateTime::RFC3339)) ?? '',
            "id" => $taskId,
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
      
        return view('livewire.sortable-item');
        
    }
    public function editTask($taskListId,$taskId)
    {
        $taskData = [
            'title' => $this->inputsTasktitle[$taskId],
            'notes' => $this->inputsTaskNotes[$taskId],
            "id" => $taskId,
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
       
        return view('livewire.sortable-item');
        
    }
    public function addTask($id)
    {
        // $task_name = $this->listarray['title_'.$id];
        
        $validatedData = $this->validate();

        $taskListId = $id; // Replace with your actual task list ID
        $taskData = [
            'title' => $this->task_name,
            // Add other task properties as needed
        ];

        $access_token = getAccessToken();

        $server_output = guzzle_post(
            "https://tasks.googleapis.com/tasks/v1/lists/".$taskListId."/tasks",
            $taskData,
            ['Accept' => 'application/json', 'Authorization' => 'Bearer ' . $access_token]
        );

        $this->task_name = '';
        $tasklistcontroller = new TasklistController();
            $tasklists =  $tasklistcontroller->lists();
            $taskListData = array();
            if(count($tasklists['tasklists']['items'])>0) : 
            
                foreach($tasklists['tasklists']['items'] as $tasklist)
                { 
                    $tasks =  $tasklistcontroller->tasks($tasklist['id']);
                    $tasks  = $tasks['tasks']['items'];
                    usort($tasks, function ($item1, $item2) {
                        return $item1['position'] <=> $item2['position'];
                    });
                    $this->taskListData[$tasklist['id']]['tasklist'] = $tasklist;
                    $this->taskListData[$tasklist['id']]['tasks'] = $tasks;
                }
            endif;
            $this->showForm = false;
            $this->showForm = true;
        // return redirect()->to('/tasklists');
    }
    public function deleteTask($taskListId,$taskId)
    {
           $access_token = getAccessToken(); // Replace with your actual access token

            $client = new \GuzzleHttp\Client();

            $response = $client->delete(
                "https://tasks.googleapis.com/tasks/v1/lists/{$taskListId}/tasks/{$taskId}",
                [
                    'headers' => [
                        'Authorization' => 'Bearer ' . $access_token,
                    ],
                ]
            );

            // if ($response->getStatusCode() == 204) {
            //     echo "Task deleted successfully";
            // } else {
            //     echo "Failed to delete task";
            // }

        // $this->task_name = '';
        $tasklistcontroller = new TasklistController();
            $tasklists =  $tasklistcontroller->lists();
            // $taskListData = array();
            if(count($tasklists['tasklists']['items'])>0) : 
            
                foreach($tasklists['tasklists']['items'] as $tasklist)
                { 
                    $tasks =  $tasklistcontroller->tasks($tasklist['id']);
                    $tasks  = $tasks['tasks']['items'];
                    usort($tasks, function ($item1, $item2) {
                        return $item1['position'] <=> $item2['position'];
                    });
                    $this->taskListData[$tasklist['id']]['tasklist'] = $tasklist;
                    $this->taskListData[$tasklist['id']]['tasks'] = $tasks;
                }
            endif;
            // $this->showForm = false;
            // $this->showForm = true;
        return redirect()->to('/tasklists');
    }
    public function confirmTaskRemoval($taskId)
    {
        $this->dispatch('confirming-task-removal', ['taskId' => $taskId]);
    }
   

    public function toggleDiv($showDivId)
    {
        
        $this->showDiv = ($this->showDiv ? 0 : 1);
        $this->showDivId = ($this->showDivId ? '' : $showDivId);
    }
    public function completeTask( $taskListId, $taskId ){

        $accessToken = getAccessToken(); // Replace with your actual access token
    
        $client = new \GuzzleHttp\Client();
    
        $taskData = [
            'status' => 'completed',
        ];
    
        $response = $client->patch(
            "https://tasks.googleapis.com/tasks/v1/lists/{$taskListId}/tasks/{$taskId}",
            [
                'headers' => [
                    'Authorization' => 'Bearer ' . $accessToken,
                    'Content-Type' => 'application/json',
                ],
                'json' => $taskData,
            ]
        );
    
        $updatedTask = json_decode($response->getBody()->getContents(), true);
    
        print_r($updatedTask);
    }
}

