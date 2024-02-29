<?php

namespace App\Livewire;

use Livewire\Component;
use App\Http\Controllers\TasklistController;

use Exception;

class SortableItem extends Component
{
    public $showDiv = 0;
    public $showDivId = '';
    public $movingTaskid;
    public $movingTasklist;
    public $currenttasklist;
    public $title;


    // public function mount($value)
    // {
    //     $this->currenttasklist = $value['tasklist']['id'];
    // }
    public function render()
    {
        try{

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
                    $taskListData[$tasklist['id']]['tasklist'] = $tasklist;
                    $taskListData[$tasklist['id']]['tasks'] = $tasks;
                }

            endif;

            // dd($taskListData);

            return view('livewire.sortable-item',compact('taskListData'));
            
        } 
        catch(Exception $e) {
            echo $e->getMessage();
            exit();
        }
       
    }
   
    // public function handleDragStart($movingTasklist,$movingTaskid)
    // {
    //    $this->movingTaskid = $movingTaskid;
    //    $this->movingTasklist = $movingTasklist;
    //    return true;
    // //  echo $this->movingTaskid;
    // //  echo "s";
    // //  die;
    //     // This code will run when the drag operation starts...
    // }
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
                    $taskListData[$tasklist['id']]['tasklist'] = $tasklist;
                    $taskListData[$tasklist['id']]['tasks'] = $tasks;
                }
            endif;

            // dd($taskListData);
            // $this->emit('refreshComponent');
            return view('livewire.sortable-item',compact('taskListData'));

            // return redirect()->to('/tasklists');
        
        // die;
    }
    public function updateGroupOrder($data)
    {
        // echo '<pre>';
        // dd($data);
    }
    public function newGroupLabel()
    {
        echo "Level";
        dd($_REQUEST);
    }
    public function removeTask($id)
    {
        echo "Level";
        dd($id);
    }
    public function addTask()
    {
        dd($_REQUEST);
        echo $this->currenttasklist;
       echo  $this->title;
       
        // / $tasklistcontroller = new TasklistController();
        //$tasklists =  $tasklistcontroller->lists();
       // dd($data);
       die;
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
}
