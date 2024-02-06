<?php

namespace App\Livewire;

use Livewire\Component;

class TaskComponent extends Component
{
    public $task_name;

    public function rules() {
        return [
            'task_name' => 'required|string'
        ];
    }
    
    public function messages() {
        return [
            'task_name.required' => 'Task name is required' 
        ];
    }
   
    public function save() 
    {
        $this->validate([
            'task_name' => 'required'
        ]);

        return redirect()->to('/task-list/');
        // return redirect()->to('/add-medicine-schedule/?mid='.$addedData->id)->with('message', 'Medicine Add Successfully!');
    }
    public function render()
    {
        // Route::get('/dashboard', function () {
        //     return view('dashboard');
        // })->name('dashboard');

        return view('dashboard')->name('dashboard');
        // return view('livewire.task-component');
        return view('dashboard');
    }
}
