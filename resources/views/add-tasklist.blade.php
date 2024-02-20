
<div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
    <div class="mt-5 md:mt-0 md:col-span-2">
        @if(Session::has('message'))
            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400" role="alert">
                <span class="font-medium">{{ Session::get('message') }}</span>
            </div>
        @endif
       
    {{-- <div class="text-center m-5">
        <button type="button" class="rounded-md bg-white/10 px-2.5 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-white/20"> <a href="{{url('medicine-list')}}">Medicine List</a></button>
    </div> --}}
      
    <form method="POST" action="{{ route('saveTask') }}">
    @csrf

        
        <div class="px-4 py-5 bg-white dark:bg-gray-800 sm:p-6 shadow sm:rounded-tl-md sm:rounded-tr-md">
            <div class="grid grid-cols-6 gap-6">
                <div class="col-span-6">
                    
                    <h1 class="text-white dark:text-gray-200 text-2xl font-semibold text-center">Add Task list</h1>
                </div>
                
                <div class="col-span-6">
                    <label class="block font-medium text-sm text-gray-700 dark:text-gray-300" for="name">
                        TaskList name
                    </label>
                    <input class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm mt-1 block w-full" id="taking_it_for" name="task_name" autocomplete="off">
                    <div class="text-red-600">@error('task_name') {{ $message }} @enderror</div>
                </div>

                
                <div class="col-span-6">
                    <button type="submit" class="rounded-md bg-white/10 px-2.5 py-1.5 text-sm font-semibold text-white shadow-sm hover:bg-white/20">
                        Add Task
                    </button>
                </div>

            </div>
        </div>
    </form>
    
    </div>
  </div>

  