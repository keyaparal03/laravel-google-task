<?php

namespace App\Livewire;

use Livewire\Component;
use App\Http\Controllers\TasklistController;

use Exception;

class SortableItem extends Component
{
    public $showDiv = 0;
    public $showDivId = '';

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
    public function updateTaskOrder($data)
    {
echo '<pre>';
     dd($data);
        // foreach ($items as $order => $item) {


        //     echo $item['value']."==".$order;
        //     echo '<br>';
        //     // Task::find($item['value'])->update(['order' => $order]);
        // }
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
