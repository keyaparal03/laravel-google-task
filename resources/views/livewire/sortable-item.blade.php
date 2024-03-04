<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="my-10 mx-10  md:col-span-2">
  
    <form wire:submit.prevent="addGroup" >
        <input type="text" wire:model="newGroupLabel">

        <button>Add Task Group</button>
    </form>


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
        

        <div wire:poll.10s wire:sortable="updateGroupOrder" wire:sortable-group="updateTaskOrder" style="display: flex;" class="grid grid-cols-1 gap-4 sm:grid-cols-3" >
            @foreach ($taskListData as $key => $value)
            
            
            <div wire:key="group-{{$value['tasklist']['id']}}" wire:sortable.item="{{$value['tasklist']['id']}}" style="display: flex; flex-direction: column;" class="relative flex items-center space-x-3 rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 hover:border-gray-400 dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700"  x-data="{ open: false }">
                <div>
                    <h4 wire:sortable.handle  class="mt-1 truncate text-bold leading-5 text-gray-500">{{$value['tasklist']['title']}} | {{$value['tasklist']['id']}}</h4>
                    
                    <button wire:click="removeGroup({{$value['tasklist']['id']}})" class="rounded-md bg-white/10 px-2.5 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-white/20">Remove</button>
                    
                    <br/>
                    
                        {{-- <button wire:click="toggleDiv('{{$value['tasklist']['id']}}')">Toggle Div</button> --}}
                        <button @click="open = !open"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                          </svg>
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
                <div>
                    {{-- wire:ignore --}}
                    <ul wire:sortable-group.item-group="{{$value['tasklist']['id']}}">
                        @foreach ($value['tasks'] as $task)
                            {{-- <li wire:key="task-{{$task['id']}}" wire:sortable-group.item="{{$task['id']}}" wire:dragstart="handleDragStart('{{$value['tasklist']['id']}}','{{$task['id']}}')" class="taskdiv" id="myElement" draggable="true" taskid="{{$task['id']}}" id="draggableElement" custom-attr="customValue" draggable="true" @dragstart="handleDragStart($event)"> --}}
                                
                                <li wire:key="task-{{$task['id']}}" wire:sortable-group.item="{{$task['id']}}" class="taskdiv" id="myElement" draggable="true" taskid="{{$task['id']}}" movingTasklist="{{$value['tasklist']['id']}}" movingTaskid="{{$task['id']}}" draggable="true" @dragstart="handleDragStart($event)">
                                <p wire:sortable-group.handle  class="mt-1 truncate text-bold leading-5 text-gray-500">{{$task['title']}} | {{$task['id']}}</p>
                                {{-- <button wire:click="removeTask({{$task['id']}})" class="rounded-md bg-white/10 px-2.5 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-white/20">Remove</button> --}}
                                    <span class="text-white">
                                        <svg  wire:click="deleteTask('{{$value['tasklist']['id']}}','{{$task['id']}}')" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </span>
                                
                            </li>
                        @endforeach
                    </ul>
                </div>
                
            </div>

            
        @endforeach
        
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
    </div>
  </div>
  