<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="my-10 mx-10  md:col-span-2">

        <div x-data="{ open: false }">
            <button @click="open = true"> <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg><span>Create</span></button>
        
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
                                    <input type="submit" value="Add Task Group" class="border bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                    {{-- <button class="border bg-blue-500 hover:bg-blue-700 text-black font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                                        Add Task Group
                                    </button> --}}
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
        

        <div wire:poll.10s wire:sortable="updateGroupOrder" wire:sortable-group="updateTaskOrder" style="display: flex;" class="grid grid-cols-1 gap-4 sm:grid-cols-3" >
            @foreach ($taskListData as $key => $value)
            
            
            <div wire:key="group-{{$value['tasklist']['id']}}" wire:sortable.item="{{$value['tasklist']['id']}}" style="display: flex; flex-direction: column;" class="relative flex items-center space-x-3 rounded-lg border border-gray-300 bg-white px-6 py-5 shadow-sm focus-within:ring-2 focus-within:ring-indigo-500 focus-within:ring-offset-2 hover:border-gray-400 dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700"  x-data="{ open: false }">
                <div>
                    <h4 wire:sortable.handle  class="mt-1 truncate text-bold leading-5 text-gray-500">{{$value['tasklist']['title']}} <div x-data="{ open: false }">
                        <button @click="open = !open"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                          </svg>
                          </button>
                    
                        <ul x-show="open" @click.away="open = false" class="absolute bg-white shadow overflow-hidden rounded w-64 mt-2 py-1 right-0 z-10">
                            <li><a href="#" class="block px-4 py-2 text-gray-800 hover:bg-indigo-500 hover:text-white">Option 1</a></li>
                            <li><a href="#" class="block px-4 py-2 text-gray-800 hover:bg-indigo-500 hover:text-white">Option 2</a></li>
                            <li><a href="#" class="block px-4 py-2 text-gray-800 hover:bg-indigo-500 hover:text-white">Option 3</a></li>
                        </ul>
                    </div></h4>
                    
                    <button wire:click="removeGroup({{$value['tasklist']['id']}})" class="rounded-md bg-white/10 px-2.5 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-white/20">Remove</button>
                    
                    <br/>
                    
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
  