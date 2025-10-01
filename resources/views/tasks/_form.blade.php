<form action="{{ route('tasks.store') }}" method="POST">
    @csrf
    <h4 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4">Add New Task</h4>
    <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
        <input hidden name="project_id" value="{{ $project->id }}" />
        <div class="md:col-span-4">
            <input type="text" name="title" placeholder="Task title" required
                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white p-2.5">
        </div>
        <div class="md:col-span-3">
            <select name="priority"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white p-2.5">
                <option value="low">Low</option>
                <option value="medium" selected>Medium</option>
                <option value="high">High</option>
            </select>
        </div>
        <div class="md:col-span-3">
            <select name="assignee_id"
                    class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white p-2.5">
                <option value="">Assign to...</option>
                @foreach($contributors as $contributor)
                    <option value="{{ $contributor->id }}">{{ $contributor->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="md:col-span-2">
            <input type="date" name="due_date"
                   class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white p-2.5">
        </div>
    </div>
    <div class="mt-4">
        <textarea name="details" placeholder="Details" rows="3"
                  class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white p-2.5"></textarea>
    </div>
    <div class="mt-4">
        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Add Task
        </button>
    </div>
</form>