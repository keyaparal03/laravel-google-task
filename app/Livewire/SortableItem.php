<?php

namespace App\Livewire;

use Livewire\Component;
use Exception;

class SortableItem extends Component
{
    public function render()
    {


        try{
            $access_token = getAccessToken();
            //
            $tasklists = guzzle_get('https://tasks.googleapis.com/tasks/v1/users/@me/lists', ['Accept' => 'application/json', 'Authorization' => 'Bearer ' . $access_token]);

            return view('livewire.sortable-item',compact('tasklists'));
            
        } 
        catch(Exception $e) {
            echo $e->getMessage();
            exit();
        }
       
    }
    public function updateTaskOrder($data)
    {
// echo '<pre>';
//      dd($data);
        // foreach ($items as $order => $item) {


        //     echo $item['value']."==".$order;
        //     echo '<br>';
        //     // Task::find($item['value'])->update(['order' => $order]);
        // }
    }
    public function updateGroupOrder($data)
    {
        echo '<pre>';
        dd($data);
    }
    public function newGroupLabel()
    {
        echo "Level";
        dd($_REQUEST);
    }
}
