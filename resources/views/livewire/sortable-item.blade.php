<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="my-10 mx-10  md:col-span-2">

        <div x-data="{ open: false }">
            <button class="add-tasklist-btn" @click="open = true"> <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                <span>Add Task List</span>
            </button>
        
            <div x-show="open" @click.away="open = false" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0 taskaddpopup">
                    <!-- Background overlay, show/hide based on modal state. -->
                    <div class="fixed inset-0 bg-transparent bg-opacity-75 transition-opacity" aria-hidden="true"></div>
        
                    <!-- This element is to trick the browser into centering the modal contents. -->
                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                    

                    <!-- Modal Content -->
                    <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full modalbody">
                        <button  @click="open = false" class="close-icon"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                          </svg>
                          </button>
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
            
                @php
                   //print_r($value['completedTasks']); 
                @endphp
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
                                
                                    <ul x-show="open" @click.away="open = false" class="tasklist-option absolute shadow overflow-hidden rounded w-64 mt-2 py-1 right-0 z-10">
                                        <li><a href="#" class="block px-4 py-2 text-white hover:bg-indigo-500 hover:text-white">Edit list</a></li>
                                        <li><a href="#" class="block px-4 py-2"> <button wire:click="removeGroup('{{$value['tasklist']['id']}}')" class="rounded-md px-2.5 py-1.5 text-sm font-semibold shadow-sm">Remove</button></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    
                        
                       
                        
                        <br/>
                        
                            <button class="text-white add-task-btn" @click="open = !open"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6 inline-block text-white">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>Add Task
                            </button>
                            <div x-show="open" class="addtaskformdiv" @click.away="open = false">
                                <div class="complete-task task-add-completebox">
                                    
                                    <span class="open-task">
                                    <svg class="open-icon" fill="white" focusable="false" width="24" height="24" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"></path></svg>
                                    <span class="done-task">
                                    
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                          </svg>
                                          
                                    </span>
                                    </span>

                                </div>
                                <div class="addformdiv">
                                
                                    <textarea class="form-edit-task-title" wire:model="task_name" wire:blur="addTask('{{$value['tasklist']['id']}}')" placeholder="Title"></textarea>

                                    <textarea class="form-edit-task-title" wire:model="task_note" wire:blur="addTask('{{$value['tasklist']['id']}}')" placeholder="Details"></textarea>

                                    <input type="date"  wire:model="task_due" class="task_due bg-gray-800 text-white border-0 focus:ring-0" wire:change="addTask('{{$value['tasklist']['id']}}')"/>
                                </div>
                            </div>
                            {{-- @endif --}}
                    </div>
                    <div class="task-second-wrap">
                        {{-- wire:ignore --}}
                        <ul wire:sortable-group.item-group="{{$value['tasklist']['id']}}">
                            @foreach ($value['tasks'] as $task)
                                <?php
                                    if($task['status'] == 'completed') continue;
                                    if (array_key_exists('parent', $task)) {
                                        continue;
                                    }
                                ?>
                                
                                <li wire:key="task-{{$task['id']}}" wire:sortable-group.item="{{$task['id']}}" class="taskdiv" id="myElement" draggable="true" taskid="{{$task['id']}}" movingTasklist="{{$value['tasklist']['id']}}" movingTaskid="{{$task['id']}}" draggable="true" @dragstart="handleDragStart($event)" x-data="{ open_: false,editform_:false,focused_: false,editedtext_:false,addopen_: false }" x-bind:style="open_ ? 'background-color: #303138;height: 200px;' : ''">
                                        
                                    <div class="each-task">
                                        {{-- <svg class="MuiSvgIcon-root" focusable="false" viewBox="0 0 24 24" aria-hidden="true" id="unchecked-icon"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"></path></svg> --}}
                                        <div class="complete-task" wire:click="completeTask('{{$value['tasklist']['id']}}','{{$task['id']}}')">
                                            {{-- @if($task['status']='needsAction') --}}
                                                <span class="open-task">
                                                    <svg class="open-icon" fill="white" focusable="false" width="24" height="24" viewBox="0 0 24 24" class=" NMm5M"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"></path></svg>
                                                    <span class="done-task">
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                                          </svg>
                                                          
                                                    </span>
                                                </span>
                                            {{-- @else --}}
                                                
                                            {{-- @endif --}}
                                            
                                        </div>
                                        
                                            
                                        <div class="task-title-wrap">
                                            <p wire:sortable-group.handle  class="mt-1 text-bold leading-5 text-white" @click="open_ = true" x-show="!open_"><input type="text" class="input_as_level" wire:model="inputsTasktitle.{{$task['id']}}"/>
                                                @if($inputsTaskNotes[$task['id']] !='')
                                                <p class="task-description" x-show="!open_"  @click="open_ = true"> {{ $inputsTaskNotes[$task['id']] }}</p>
                                                @endif
                                                @if($inputsTaskDueDateFormatted[$task['id']] !='')
                                                
                                                <p class="task-due" x-show="!open_"  @click="open_ = true"> {{ $inputsTaskDueDateFormatted[$task['id']] }}</p>
                                                @endif
                                            </p>


                                            <div class="addsubformdiv" @click.away="addopen_ = false" x-show="addopen_" wire:click.away="addSubTask('{{$value['tasklist']['id']}}','{{$task['id']}}')">
                                                <div class="complete-task task-add-completebox">
                                                    
                                                    <span class="open-task">
                                                    <svg class="open-icon" fill="white" focusable="false" width="24" height="24" viewBox="0 0 24 24"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"></path></svg>
                                                    <span class="done-task">
                                                    
                                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                                          </svg>
                                                          
                                                    </span>
                                                    </span>
                
                                                </div>
                                                <div class="addformdiv">
                                                
                                                    <textarea class="form-edit-task-title" wire:model="addsubtasktitle" wire:blur="addSubTask('{{$value['tasklist']['id']}}','{{$task['id']}}')" placeholder="Title"></textarea>

                                                    <textarea class="form-edit-task-title" wire:model="addsubtasknotes" wire:blur="addSubTask('{{$value['tasklist']['id']}}','{{$task['id']}}')" placeholder="Title"></textarea>


                                                    <input type="date"  wire:model="addsubtaskdue" class="task_due bg-gray-800 text-white border-0 focus:ring-0" wire:change="addSubTask('{{$value['tasklist']['id']}}','{{$task['id']}}')"/>

                                                </div>
                                            </div>







                                            <div @click.away="open_ = false"  x-show="open_" class="editformdiv">
                                                <textarea class="form-edit-task-title" wire:model="inputsTasktitle.{{$task['id']}}" wire:blur="editTask('{{$value['tasklist']['id']}}','{{$task['id']}}')" ></textarea>
                                                <textarea class="form-edit-task-title" placeholder="details" wire:model="inputsTaskNotes.{{$task['id']}}" wire:blur="editTask('{{$value['tasklist']['id']}}','{{$task['id']}}')"></textarea>
                                                
                                                <input type="date"  wire:model="inputsTaskDueDate.{{$task['id']}}" class="task_due bg-gray-800 text-white border-0 focus:ring-0" wire:change="editTaskDueDate('{{$value['tasklist']['id']}}','{{$task['id']}}')" value="{{ $inputsTaskDueDate[$task['id']] }}"/>
                                            
                                            </div>
                                        </div>
                                        <div class="tasklistedit">
                                            <div x-data="{ open: false }">
                                                <button @click="open = !open"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                                                </svg>
                                                </button>
                                            
                                                <ul x-show="open" @click.away="open = false" class="task-option absolute shadow overflow-hidden rounded w-64 mt-2 py-1 right-0 z-10">
                                                    <li><a href="#" class="block px-4 py-2 text-white hover:bg-indigo-500 hover:text-white"> <button wire:click="deleteTask('{{$value['tasklist']['id']}}','{{$task['id']}}')" class="rounded-md bg-white/10 px-2.5 py-1.5 text-sm font-semibold shadow-sm ">Remove</button></a></li>
                                                    <li><a href="#" class="block px-4 py-2 text-white hover:bg-indigo-500 hover:text-white"  @click="open_ = true;open = false;" >Edit</a></li>
                                                    <li><a href="#" class="block px-4 py-2 text-white hover:bg-indigo-500 hover:text-white"  @click="addopen_ = true;open = false;" >Add Subtask</a></li>
                                                    <li><a href="#" class="block px-4 py-2 text-white hover:bg-indigo-500 hover:text-white"> <button wire:click="duplicateTask('{{$value['tasklist']['id']}}','{{$task['id']}}')" class="rounded-md bg-white/10 px-2.5 py-1.5 text-sm font-semibold shadow-sm ">Duplicate Task</button></a></li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                
                                
                                </li>
                                
                              
                                @if(array_key_exists("{$task['id']}", $subtask))

                                    @foreach ($subtask[$task['id']] as $stask)


                                    <li wire:key="task-{{$stask['id']}}" wire:sortable-group.item="{{$stask['id']}}" class="taskdiv indent" id="myElement" draggable="true" taskid="{{$stask['id']}}" movingTasklist="{{$value['tasklist']['id']}}" movingTaskid="{{$stask['id']}}" draggable="true" @dragstart="handleDragStart($event)" x-data="{ open_: false,editform_:false,focused_: false,editedtext_:false}"  x-bind:style="open_ ? 'background-color: #303138;height: 200px;' : ''">
    
                                        <div class="each-task">
                                          
                                            <div class="complete-task" wire:click="completeTask('{{$value['tasklist']['id']}}','{{$stask['id']}}')">
                                                {{-- @if($stask['status']='needsAction') --}}
                                                    <span class="open-task">
                                                        <svg class="open-icon" fill="white" focusable="false" width="24" height="24" viewBox="0 0 24 24" class=" NMm5M"><path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.42 0-8-3.58-8-8s3.58-8 8-8 8 3.58 8 8-3.58 8-8 8z"></path></svg>
                                                        <span class="done-task">
                                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                                <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                                              </svg>
                                                        </span>
                                                    </span>
                                                {{-- @else --}}
                                                    
                                                {{-- @endif --}}
                                                
                                            </div>
                                            
                                                
                                            <div class="task-title-wrap">
                                                <p wire:sortable-group.handle  class="mt-1 text-bold leading-5 text-white" @click="open_ = true" x-show="!open_"><input type="text" class="input_as_level" wire:model="inputsTasktitle.{{$stask['id']}}"/>
                                                    @if($inputsTaskNotes[$stask['id']] !='')
                                                    <p class="task-description" x-show="!open_"  @click="open_ = true"> {{ $inputsTaskNotes[$stask['id']] }}</p>
                                                    @endif
                                                    @if($inputsTaskDueDateFormatted[$stask['id']] !='')
                                                    
                                                    <p class="task-due" x-show="!open_"  @click="open_ = true"> {{ $inputsTaskDueDateFormatted[$stask['id']] }}</p>
                                                    @endif
                                                </p>
                                              
                                                <div @click.away="open_ = false"  x-show="open_" class="editformdiv">
                                                    <textarea class="form-edit-task-title" wire:model="inputsTasktitle.{{$stask['id']}}" wire:blur="editTask('{{$value['tasklist']['id']}}','{{$stask['id']}}')" ></textarea>
                                                    <textarea class="form-edit-task-title" placeholder="details" wire:model="inputsTaskNotes.{{$stask['id']}}" wire:blur="editTask('{{$value['tasklist']['id']}}','{{$stask['id']}}')"></textarea>
                                                    
                                                    <input type="date"  wire:model="inputsTaskDueDate.{{$stask['id']}}" class="task_due bg-gray-800 text-white border-0 focus:ring-0" wire:change="editTaskDueDate('{{$value['tasklist']['id']}}','{{$stask['id']}}')" value="{{ $inputsTaskDueDate[$stask['id']] }}"/>
                                                
                                                </div>
                                            </div>
                                            <div class="tasklistedit">
                                                <div x-data="{ open: false }">
                                                    <button @click="open = !open"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 12.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5ZM12 18.75a.75.75 0 1 1 0-1.5.75.75 0 0 1 0 1.5Z" />
                                                    </svg>
                                                    </button>
                                                
                                                    <ul x-show="open" @click.away="open = false" class="task-option absolute shadow overflow-hidden rounded w-64 mt-2 py-1 right-0 z-10">
                                                        <li><a href="#" class="block px-4 py-2 text-white hover:bg-indigo-500 hover:text-white"> <button wire:click="deleteTask('{{$value['tasklist']['id']}}','{{$stask['id']}}')" class="rounded-md bg-white/10 px-2.5 py-1.5 text-sm font-semibold shadow-sm ">Remove</button></a></li>
                                                        <li><a href="#" class="block px-4 py-2 text-white hover:bg-indigo-500 hover:text-white"  @click="open_ = true;open = false;" >Edit</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    
                                    
                                    </li>

                                    @endforeach
                                @endif
                                
                               
                            @endforeach
                            @foreach ($value['tasks'] as $task)
                            <?php
                                if($task['status'] == 'completed'){
                            ?>
                             <li wire:key="task-{{$task['id']}}" wire:sortable-group.item="{{$task['id']}}" class="taskdiv" id="myElement">
                                    
                                <div class="each-task">
                                    <div class="complete-task" wire:click="changeTaskStatus('{{$value['tasklist']['id']}}','{{$task['id']}}')">
                                        {{-- @if($task['status']='needsAction') --}}
                                            <span class="open-task complete-open-task">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                                  </svg>
                                                  
                                            </span>
                                        {{-- @else --}}
                                            
                                        {{-- @endif --}}
                                        
                                    </div>
                                    <div class="task-title-wrap">
                                        <p class="mt-1 text-bold leading-5 text-white completed-title">{{$task['title']}}</p>
                                    </div>
                                    <div class="tasklistedit">
                                        <a href="#"> <button wire:click="changeTaskStatus('{{$value['tasklist']['id']}}','{{$task['id']}}')" class="rounded-md bg-white/10 px-2.5 py-1.5 text-sm font-semibold shadow-sm "><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                          </svg>
                                          </button></a>
                                    </div>
                                </div>
                            
                            </li>
                            <?php
                                }else{
                                    continue;
                            } ?>
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
    margin: 2px;
    max-width: 300px;
    min-width: 300px;
    overflow: clip;
    padding: 0;
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
    margin-left: -20px;
    margin-right: -20px;
}
.task-second-wrap{
    width: 100%;
}
li.taskdiv {
    border-radius: 9px;
    color: #000;
    margin-bottom: 15px;
    height: 60px;
    margin-top: 15px;
    display: block;
    padding-left: 10px;
    padding-right: 5px;
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
    width: 81%;
    float: left;
}
main {
    background: #1F2024;
    height: 570px;
}
button.text-white.add-task-btn {
    font-size: 14px;
    margin-left: 10px;
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
    font-size: 14px;
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
textarea.input_as_level {
    background: none;
    border: none;
    resize: none;
}
button.close-icon {
    right: 0!important;
    position: absolute;
    padding: 3px;
}
.taskaddpopup{
    margin-top: 100px;
}
textarea.form-edit-task-title {
    background: #303138;
    border: none;
    color: #ccc;
    resize: none;
    width: 100%;
    font-size: 13px;

}
.task-description {
    display: inline-block;
    /* font-style: italic; */
    font-size: 13px;
    color: #5f6368;
}
.task-due {
    overflow: hidden;
    white-space: nowrap;
    text-overflow: ellipsis;
    background-color: #27272B;
    border: 1px #fff;
    box-sizing: content-box;
    height: 1rem;
    padding: 1px 0px 2px 16px;
    margin-right: 4px;
    display: flex;
    color: #2684FC;
    font-size: 11px;
    width: 93px;
    border-radius: 22px;
    width: 79px;
    margin-top: -5px;
}
span.done-task, span.open-task {
    cursor: pointer;
}
.complete-task {
    display: flex;
    width: 10%;
    float: left;
    margin-top: 13px;
    padding-right: 8px;
}
.tasklistedit {
    display: flex;
}
.done-task {
    display: none;
}

.open-task:hover .done-task {
    display: inline;
}
.open-task:hover .open-icon {
    display: none;
}
.completed-title {
    text-decoration: line-through;
}
li.taskdiv.indent {
    padding-left: 20px!important;
}
li.taskdiv:hover {
    background: #303138;
}
.titlewrap {
    padding-top: 10px;
    padding-left: 10px;
    padding-right: 5px;
}
.task-option {
    background: #3A3B40;
    font-size: 12px;
}
.tasklist-option{
    background: #3A3B40;
    font-size: 12px;
}
button.add-task-btn:hover svg {
    color: #2684FC;
}
button.add-task-btn:hover {
    color: #2684FC;
}
.addtaskformdiv{
    background: #303138;
    height: 200px;
}
.addformdiv {
    float: right;
    width: 85%;
    padding-right: 20px;
}
.complete-task.task-add-completebox {
    margin-left: 10px;
}
.task_due {
    font-size: 12px;
    width: 100%;
}
.tasklistedit {
    display: flex;
    color: #5D6166;
    align-items: center;
}
span.open-task {
    cursor: pointer;
    color: #7BACFC;
}

span.open-task.complete-open-task {
    margin-top: -10px;
}
span.open-task.complete-open-task:hover {
    margin-top: -10px;
    border-radius: 25px;
    background: #6D7075;
}
main {
    background: #1F2024;
    height: 100%;
}
 </style>
    </div>
  </div>