<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="my-10 mx-10  md:col-span-2">

        <div x-data="{ open: false }">
            <button class="add-tasklist-btn" @click="open = true"> <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <span>Add Task List</span>
            </button>
        
            <div x-show="open" @click.away="open = false" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <!-- Background overlay, show/hide based on modal state. -->
                    <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        
                    <!-- This element is to trick the browser into centering the modal contents. -->
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
                    <!-- Modal Content -->
                    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <form wire:submit="addTaskList" class="bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4">
                                <div class="mb-4">
                                    <input  wire:model="addlisttitle"  class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" type="text" value="" placeholder="Add title" autofocus="" maxlength="1023" autocomplete="off">
                                </div>
                                <div class="mb-6">
                                    <textarea  wire:model="addlistdescription" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" rows="3" cols="" placeholder="Add description" maxlength="4095" data-is-auto-expanding="true" data-min-rows="3"></textarea>
                                </div>
                                <div class="flex items-center justify-between">
                                    <input type="submit" value="Add Task Group" class="border bg-blue-500 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                  
                                </div>
                            </form>
                        </div>
                        {{-- <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="button" @click="open = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Close
                            </button>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
       
  


    {{-- <div x-data="{ open: false, handleDragStart: () => window.livewire.emit('dragStarted') }" 
        @dragstart="handleDragStart"
        draggable="true">
       <button @click="open = !open">Toggle</button>
   
       <div x-show="open">
           Hello World!
       </div>
   </div> --}}

  
        <input type="hidden" id="movingTasklist" wire:model="movingTasklist">
        <input type="hidden" id="movingTaskid" wire:model="movingTaskid">
        
        <div class="taskmainwrap">
            <div wire:poll.10s wire:sortable="updateGroupOrder" wire:sortable-group="updateTaskOrder" style="display: flex;" class="grid grid-cols-1 gap-4 sm:grid-cols-3" >
                @foreach ($taskListData as $key => $value)
                
                
                <div wire:key="group-{{$value['tasklist']['id']}}" wire:sortable.item="{{$value['tasklist']['id']}}" style="display: flex; flex-direction: column;" class="relative flex items-center space-x-3 rounded-lg bg-white px-6 py-5 shadow-sm focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 list"  x-data="{ open: false,editform:false,focused: false,editedtext:false }">
                    <div class="task-wrap">
                        <div class="titlewrap">
                            <div class="listtitle">
                                {{-- wire:loading.remove wire:target="inputValue" --}}
                                
                                <div>
                                    {{ $inputValue }}
                                </div>
                                <div>
                                   
                                    {{-- <h4 wire:sortable.handle @click="editform = !editform" class="mt-1 text-bold leading-5 text-white">
                                    {{-- <span>{{$inputs[$value['tasklist']['id']]}}</span> 
                                </h4> --}}
                                <h4> <input type="text"  @click="editform = !editform"  class="input_as_level" wire:model="inputs.{{$value['tasklist']['id']}}"  x-show="!editform">
                                </h4>
                                    <form wire:submit.prevent="editTaskList('{{$value['tasklist']['id']}}')">
                                        <input type="text" x-show="editform" class="form-edit-title" wire:model="inputs.{{$value['tasklist']['id']}}" wire:blur="editTaskList('{{$value['tasklist']['id']}}')"  @focus="focused = false" @blur="focused = true; editform = false; editedtext = true">
                                        <!-- Other form fields... -->
                                    </form>
                                    
                                </div>
                            </div>
                            <div class="listedit">
                                <div x-data="{ open: false }">
                                    <button @click="open = !open"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                                    </svg>
                                    </button>
                                
                                    <ul x-show="open" @click.away="open = false" class="tasklist-option absolute bg-white shadow overflow-hidden rounded w-64 mt-2 py-1 right-0 z-10">
                                        <li><a href="#" class="block px-4 py-2 text-gray-800 hover:bg-indigo-500 hover:text-white">Edit list</a></li>
                                        <li><a href="#" class="block px-4 py-2"> <button wire:click="removeGroup('{{$value['tasklist']['id']}}')" class="rounded-md px-2.5 py-1.5 text-sm font-semibold shadow-sm">Remove</button></a></li>
                                        
                                        <li><a href="#" class="block px-4 py-2 text-gray-800 hover:bg-indigo-500 hover:text-white">Option 3</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    
                        
                       
                        
                        <br/>
                        
                            <button class="text-white add-task-btn" @click="open = !open"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 inline-block text-white">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>Add Task
                            </button>

                            {{-- @if($showDiv==1 && $value['tasklist']['id']==$showDivId) --}}
                            @if($showForm)
                            <div x-show="open">
                                <form wire:submit.prevent="addTask('{{$value['tasklist']['id']}}')">
                                    <input type="text" name="title"  wire:model="task_name"></br>
                                    @error('task_name') <span class="mt-2 text-sm text-red-600" style="display: block">{{ $message }}</span> @enderror
                                
                                    <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"  @click="open = false">Add Task</button>
                                </form>
                            </div>
                            @endif
                            {{-- @endif --}}
                    </div>
                    <div class="task-second-wrap">
                        {{-- wire:ignore --}}
                        <ul wire:sortable-group.item-group="{{$value['tasklist']['id']}}">
                            @foreach ($value['tasks'] as $task)
                            
                                    <li wire:key="task-{{$task['id']}}" wire:sortable-group.item="{{$task['id']}}" class="taskdiv" id="myElement" draggable="true" taskid="{{$task['id']}}" movingTasklist="{{$value['tasklist']['id']}}" movingTaskid="{{$task['id']}}" draggable="true" @dragstart="handleDragStart($event)">
                                        <div class="each-task">
                                            <div class="task-title-wrap">
                                                <p wire:sortable-group.handle  class="mt-1 text-bold leading-5 text-white">{{$task['title']}}</p>
                                            </div>
                                            <div class="tasklistedit">
                                                <div x-data="{ open: false }">
                                                    <button @click="open = !open"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                                                    </svg>
                                                    </button>
                                                
                                                    <ul x-show="open" @click.away="open = false" class="task-option absolute bg-white shadow overflow-hidden rounded w-64 mt-2 py-1 right-0 z-10">
                                                        <li><a href="#" class="block px-4 py-2 text-gray-800 hover:bg-indigo-500 hover:text-white"> <button wire:click="deleteTask('{{$value['tasklist']['id']}}','{{$task['id']}}')" class="rounded-md bg-white/10 px-2.5 py-1.5 text-sm font-semibold shadow-sm ">Remove</button></a></li>
                                                        <li><a href="#" class="block px-4 py-2 text-gray-800 hover:bg-indigo-500 hover:text-white">Option 2</a></li>
                                                        <li><a href="#" class="block px-4 py-2 text-gray-800 hover:bg-indigo-500 hover:text-white">Option 3</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                   
                                    
                                </li>
                            @endforeach
                        </ul>
                    </div>
                    
                </div>

                
                @endforeach
            
            </div>
        </div>
        <script>
           
            function handleDragStart(event) {
                var elementId = event.target.id;
                var movingTasklist = event.target.getAttribute('movingTasklist');
                var movingTaskid = event.target.getAttribute('movingTaskid');
                console.log('Element ID: ' + movingTasklist);
                console.log('Custom Attribute: ' + movingTaskid);
                document.getElementById('movingTasklist').value = movingTasklist;
                document.getElementById('movingTaskid').value = movingTaskid;
            }
        </script>

  <style>

    .listtitle {
    color: #535458;
    font-size: 16px;
    line-height: 20px;
    min-height: 20px;
    overflow-wrap: break-word;
    word-wrap: break-word;
    /* white-space: pre-wrap;
    letter-spacing: normal; */
    overflow: hidden;
    width: 90%;
    float: left;
}

.list {
    background: #292A2F;
    flex-shrink: 1;
    flex-grow: 1;
    margin: 4px;
    max-width: 300px;
    min-width: 300px;
    overflow: clip;
}
main {
    background: #1F2024;
}
.taskmainwrap {
    /* overflow-x: scroll;
    cursor: grab;
    height: 100vh;  */
    overflow-x: scroll;
    cursor: grab;
    align-items: flex-start;
    -webkit-box-pack: start;
    justify-content: flex-start;
    flex-direction: column;
    width: 100%;
    padding-top: 56px;
}
.task-wrap {
    width: 100%;
}
.task-second-wrap{
    width: 100%;
}
li.taskdiv {
    /* background-color: #6B7280; */
    border-radius: 9px;
    color: #000;
    margin-bottom: 15px;
    height: 30px;
    margin-top: 15px;
}
.tasklist-option {
    right: 0 !important;
}
.task-option {
    right: 0 !important;
}
.leading-5 {
    line-height: 1.25rem;
    word-break: break-all;
}
/* .taskmainwrap {
    display: flex;
    align-items: flex-start;
    -webkit-box-pack: start;
    justify-content: flex-start;
    flex-direction: column;
    width: 100%;
    padding-top: 56px;
} */
.task-title-wrap {
    width: 90%;
    float: left;
}
main {
    background: #1F2024;
    height: 570px;
}
button.text-white.add-task-btn {
    font-size: 15px;
}
button.add-tasklist-btn {
    display: block;
    background: #292A2F;
    padding: 10px 30px;
    color: #ccc;
    width: 100%;
}
input.form-edit-title {
    background: none;
    border: navajowhite;
    border-bottom: 1px solid;
}
input.input_as_level {
    background: none;
    border: none;
    color: #ffff;
    margin: 0!important;
    padding-left: 0px;
}

input.form-edit-title {
    color: #fff;
    border: navajowhite;
    --tw-ring-offset-color: #fff!important;
    --tw-ring-color: none!important;
    --tw-ring-offset-shadow: var(--tw-ring-inset) 0 0 0 var(--tw-ring-offset-width) var(--tw-ring-offset-color)!important;
    --tw-ring-shadow: none!important;
    padding-left: 0px;
}
 </style>
    </div>
  </div>