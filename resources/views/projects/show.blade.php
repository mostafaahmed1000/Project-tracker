<x-layouts.app :title="$project->name">
    <div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="flex items-center space-x-2 justify-between m-2">
            @can('update', $project)
                <a href="{{ route('projects.edit', $project) }}"
                    class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-yellow-600 hover:bg-yellow-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500">Edit
                    Project</a>
            @endcan
            <a href="{{ route('projects.index') }}"
                class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 font-medium ">
                &larr; Back to Projects
            </a>
        </div>
        <div class="flex justify-between items-center mb-4">

            <div>
                <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200">{{ $project->name }}</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-1">{{ $project->description }}</p>
                <div class="flex items-center space-x-4 mt-2 text-sm text-gray-500 dark:text-gray-400">
                    <span>Status: <strong
                            class="font-semibold text-gray-700 dark:text-gray-300">{{ ucfirst($project->status) }}</strong></span>
                    <span>Due: <strong
                            class="font-semibold text-gray-700 dark:text-gray-300">{{ $project->due_date }}</strong></span>
                </div>
            </div>

        </div>

        <hr class="my-6 border-gray-200 dark:border-gray-700">

        <div>
            <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-200 mb-4">Tasks</h3>

            @can('create', [App\Models\Task::class, $project])
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6 mb-6">
                    @include('tasks._form', [
                        'project' => $project,
                        'task' => new \App\Models\Task(),
                        'contributors' => $contributors,
                    ])
                </div>
            @endcan

            <div class="shadow overflow-hidden border-b border-gray-200 dark:border-gray-700 sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Done</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Title</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Priority</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Assignee</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Due</th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Actions</th>
                        </tr>
                    </thead>
                </table>
            </div>

            <div class="shadow overflow-hidden border-b border-gray-200 dark:border-gray-700 sm:rounded-lg mt-6"
                x-data="{ tab: 'active' }">
                <div class="px-6 py-4 bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-700">
                    <nav class="flex space-x-4" aria-label="Tabs">
                        <button @click="tab = 'active'"
                            :class="tab === 'active' ? 'border-indigo-500 text-indigo-600' :
                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap pb-2 px-1 border-b-2 font-medium text-sm">
                            Active Tasks
                        </button>
                        <button @click="tab = 'trashed'"
                            :class="tab === 'trashed' ? 'border-indigo-500 text-indigo-600' :
                                'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                            class="whitespace-nowrap pb-2 px-1 border-b-2 font-medium text-sm">
                            Deleted Tasks
                        </button>
                    </nav>
                </div>
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <tbody x-show="tab === 'active'" style="display: none;"
                        class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($project->tasks as $task)
                            <tr id="task-{{ $task->id }}" class="hover:bg-gray-50 dark:hover:bg-gray-800/50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @can('update', $task)
                                        <input type="checkbox" data-id="{{ $task->id }}"
                                            class="toggle-done h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500"
                                            {{ $task->is_done ? 'checked' : '' }}>
                                    @endcan

                                </td>
                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $task->title }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ ucfirst($task->priority) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $task->assignee?->name ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $task->due_date }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    @can('update', $task)
                                        <a href="{{ route('tasks.edit', [$project, $task]) }}"
                                            class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300">Edit</a>
                                    @endcan
                                    @can('delete', $task)
                                        <form action="{{ route('tasks.destroy', $task) }}" method="POST"
                                            class="inline-block">
                                            @csrf @method('DELETE')
                                            <button
                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                                onclick="return confirm('Delete task?')">Delete</button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                            <tr class="bg-gray-50 dark:bg-gray-900/50">
                                <td colspan="6" class="px-6 py-4">
                                    @include('attachments._list', [
                                        'attachments' => $task->attachments,
                                        'task' => $task,
                                    ])
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6"
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No tasks
                                    for this project yet.</td>
                            </tr>
                        @endforelse
                    </tbody>
                    <tbody x-show="tab === 'trashed'" style="display: none;"
                        class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($trashedTasks as $task)
                            <tr id="trashed-task-{{ $task->id }}"
                                class="hover:bg-gray-50 dark:hover:bg-gray-800/50">

                                <td
                                    class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                                    {{ $task->title }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ ucfirst($task->priority) }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $task->assignee?->name ?? '-' }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                    {{ $task->due_date }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                                    @can('restore', $task)
                                        <form action="{{ route('tasks.restore', $task->id) }}" method="POST"
                                            class="inline-block">
                                            @csrf
                                            <button
                                                class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">Restore</button>
                                        </form>
                                    @endcan
                                    @can('forceDelete', $task)
                                        <form action="{{ route('tasks.force-delete', $task->id) }}" method="POST"
                                            class="inline-block">
                                            @csrf @method('DELETE')
                                            <button
                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300"
                                                onclick="return confirm('Permanently delete project? This cannot be undone.')">Delete
                                                Permanently</button>
                                        </form>
                                    @endcan
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6"
                                    class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No deleted
                                    tasks.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.toggle-done').forEach(cb => {
                cb.addEventListener('change', function() {
                    let taskId = this.dataset.id;
                    let url = "{{ url('/tasks') }}/" + taskId + "/toggle-done";

                    fetch(url, {
                            method: 'PATCH',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({})
                        })
                        .then(res => res.json())
                        .then(data => {
                            console.log('Updated', data);
                        });
                });
            });
        });
    </script>
</x-layouts.app>
