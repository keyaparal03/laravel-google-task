
<div>
    {{-- Care about people's approval and you will be their prisoner. --}}
    @livewireScripts
    <script src="https://cdn.jsdelivr.net/gh/livewire/sortable@v1.x.x/dist/livewire-sortable.js"></script>

{{-- 
    <ul wire:sortable="updateTaskOrder" wire:sortable.options="{ animation: 100 }">
        @foreach ($tasklists['items'] as $task)
            <li wire:sortable.item="{{ $task['id'] }}" wire:key="task-{{ $task['id'] }}">
                <h4>{{  $task['title'] }}</h4>
                <button wire:sortable.handle>drag</button>
            </li>
        @endforeach
    </ul> --}}

    <ul wire:sortable="updateTaskOrder">
        {{-- @foreach ($tasks as $task) --}}
            <li wire:sortable.item="aaa" wire:key="task-aaa">
                <h4 wire:sortable.handle>test1</h4>
                <button wire:click="removeTask(1)">Remove</button>
            </li>
            <li wire:sortable.item="bbb" wire:key="task-bbb">
                <h4 wire:sortable.handle>test12</h4>
                <button wire:click="removeTask(2)">Remove</button>
            </li>
        {{-- @endforeach --}}
    </ul>

    <div wire:sortable="updateGroupOrder" wire:sortable-group="updateTaskOrder" style="display: flex">
        {{-- @foreach ($groups as $group) --}}
            <div wire:key="group-group1" wire:sortable.item="group1">
                <div style="display: flex">
                    <h4 wire:sortable.handle>Group1</h4>
    
                    <button wire:click="removeGroup(1)">Remove</button>
                </div>
    
                <ul wire:sortable-group.item-group="group1">
                    {{-- @foreach ($group->tasks()->orderBy('order')->get() as $task) --}}
                        <li wire:key="task-task11" wire:sortable-group.item="task11">
                            <span wire:sortable-group.handle>task1group1</span>
                            <button wire:click="removeTask(task11)">Remove</button>
                        </li>
                        <li wire:key="task-task12" wire:sortable-group.item="task12">
                            <span wire:sortable-group.handle>task2group1</span>
                            <button wire:click="removeTask(task12)">Remove</button>
                        </li>
                    {{-- @endforeach --}}
                </ul>
    
                <form wire:submit.prevent="addTask(task1, $event.target.title.value)">
                    <input type="text" name="title">
    
                    <button>Add Task</button>
                </form>
            </div>

            {{-- two --}}

            <div wire:key="group-group2" wire:sortable.item="group2">
                <div style="display: flex">
                    <h4 wire:sortable.handle>Group2</h4>
    
                    <button wire:click="removeGroup(2)">Remove</button>
                </div>
    
                <ul wire:sortable-group.item-group="group2">
                    {{-- @foreach ($group->tasks()->orderBy('order')->get() as $task) --}}
                    <li wire:key="task-task21" wire:sortable-group.item="task21">
                        <span wire:sortable-group.handle>task1group2</span>
                        <button wire:click="removeTask(task21)">Remove</button>
                    </li>
                    <li wire:key="task-task22" wire:sortable-group.item="task22">
                        <span wire:sortable-group.handle>task2group2</span>
                        <button wire:click="removeTask(task22)">Remove</button>
                    </li>
                    {{-- @endforeach --}}
                </ul>
    
                <form wire:submit.prevent="addTask(task22, $event.target.title.value)">
                    <input type="text" name="title">
    
                    <button>Add Task</button>
                </form>
            </div>
        {{-- @endforeach --}}
    
        <form wire:submit.prevent="addGroup">
            <input type="text" wire:model="newGroupLabel">
    
            <button>Add Task Group</button>
        </form>
    </div>
    <ul class="list-inside ...">
        <li>5 cups chopped Porcini mushrooms</li>
        <!-- ... -->
      </ul>
      
      <ul class="list-outside ...">
        <li>5 cups chopped Porcini mushrooms</li>
        <!-- ... -->
      </ul>
    
<h2 class="mb-2 text-lg font-semibold text-gray-900 dark:text-white">Password requirements:</h2>
<ul class="max-w-md space-y-1 text-gray-500 list-none list-inside dark:text-gray-400">
    <li>
        At least 10 characters (and up to 100 characters)
    </li>
    <li>
        At least one lowercase character
    </li>
    <li>
        Inclusion of at least one special character, e.g., ! @ # ?
    </li>
</ul>

</div>
