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
                    $taskListData[$tasklist['id']]['tasklist'] = $tasklist;
                    $taskListData[$tasklist['id']]['tasks'] = $tasks['tasks']['items'];
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
   
    public function handleDragStart($movingTasklist,$movingTaskid)
    {
       $this->movingTaskid = $movingTaskid;
       $this->movingTasklist = $movingTasklist;
       return true;
    //  echo $this->movingTaskid;
    //  echo "s";
    //  die;
        // This code will run when the drag operation starts...
    }
    public function updateTaskOrder($data)
    {
        foreach ($data as $order => $item) {

            if($item['value']==$this->movingTasklist) continue;
           
            foreach($item['items'] as $task)
            {
            
                if($task['value'] == $this->movingTaskid)
                {

                    dd($task);
                    
                    $newTaskListid = $item['value'];

                    $access_token = getAccessToken();

                    $server_output = guzzle_get("https://tasks.googleapis.com/tasks/v1/lists/".$this->movingTasklist."/tasks/".$this->movingTaskid, ['Accept' => 'application/json', 'Authorization' => 'Bearer ' . $access_token]);
            
            
                    // $server_output3 = guzzle_delete("https://tasks.googleapis.com/tasks/v1/lists/".$this->movingTasklist."/tasks/".$this->movingTaskid, ['Accept' => 'application/json', 'Authorization' => 'Bearer ' . $access_token]);
            
                    $server_output2 = guzzle_post(
                        "https://tasks.googleapis.com/tasks/v1/lists/".$newTaskListid."/tasks",
                        $server_output,
                        ['Accept' => 'application/json', 'Authorization' => 'Bearer ' . $access_token]
                    );

                    $client = new \GuzzleHttp\Client();

                    $response = $client->delete('https://tasks.googleapis.com/tasks/v1/lists/' . $this->movingTasklist . '/tasks/' . $this->movingTaskid, [
                        'headers' => [
                            'Authorization' => 'Bearer ' . $access_token,
                            'Accept' => 'application/json'
                        ]
                    ]);

                }
            }
        }
        $tasklistcontroller = new TasklistController();
            $tasklists =  $tasklistcontroller->lists();
            $taskListData = array();
            if(count($tasklists['tasklists']['items'])>0) : 
            
                foreach($tasklists['tasklists']['items'] as $tasklist)
                { 
                    $tasks =  $tasklistcontroller->tasks($tasklist['id']);
                    $taskListData[$tasklist['id']]['tasklist'] = $tasklist;
                    $taskListData[$tasklist['id']]['tasks'] = $tasks['tasks']['items'];
                }

            endif;

            // dd($taskListData);

            return view('livewire.sortable-item',compact('taskListData'));
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
