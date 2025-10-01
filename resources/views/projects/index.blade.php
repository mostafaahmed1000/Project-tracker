<x-layouts.app :title="__('Projects')">
<div class="container mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex justify-between items-center">
        <h1 class="text-2xl font-bold mb-4 text-gray-800 dark:text-gray-200">Projects</h1>
        @can('create', App\Models\Project::class)
            <a href="{{ route('projects.create') }}" class="inline-flex items-center px-4 py-2 bg-green-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-green-500 active:bg-green-700 focus:outline-none focus:border-green-700 focus:ring ring-green-300 disabled:opacity-25 transition ease-in-out duration-150 mb-4">+ New Project</a>
        @endcan
    </div>

    <div class="border-b border-gray-200 dark:border-gray-700 mb-4">
        <nav class="-mb-px flex space-x-8" aria-label="Tabs">
            <a href="{{ route('projects.index') }}" class="{{ !request('trashed') ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                All Projects
            </a>
            <a href="{{ route('projects.index', ['trashed' => true]) }}" class="{{ request('trashed') ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }} whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                Trash
            </a>
        </nav>
    </div>

    @if(!request('trashed'))
        {{-- Filters --}}
        <form method="GET" action="{{ route('projects.index') }}" class="mb-4 grid grid-cols-1 md:grid-cols-12 gap-4 items-center">
            <div class="md:col-span-4">
                <input type="text" name="search" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white p-2.5 text-sm" placeholder="Search by name"
                       value="{{ request('search') }}">
            </div>
            <div class="md:col-span-3">
                <select name="status" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white p-2.5 text-sm">
                    <option value="">All Statuses</option>
                    @foreach(['planned','active','paused','completed'] as $status)
                        <option value="{{ $status }}" {{ request('status') === $status ? 'selected' : '' }}>
                            {{ ucfirst($status) }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="md:col-span-3">
                <select name="sort" class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:border-gray-600 dark:text-white p-2.5 text-sm">
                    <option value="asc" {{ request('sort')==='asc'?'selected':'' }}>Due Date ↑</option>
                    <option value="desc" {{ request('sort')==='desc'?'selected':'' }}>Due Date ↓</option>
                </select>
            </div>
            <div class="md:col-span-2">
                <button class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Filter</button>
            </div>
        </form>
    @endif

    <div class="shadow overflow-hidden border-b border-gray-200 dark:border-gray-700 sm:rounded-lg">
        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
            <thead class="bg-gray-50 dark:bg-gray-800">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Name</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">{{ request('trashed') ? 'Deleted At' : 'Due Date' }}</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Owner</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white dark:bg-gray-900 divide-y divide-gray-200 dark:divide-gray-700">
                @forelse($projects as $project)
                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-800/50 transition duration-150 ease-in-out">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-white">
                            <a href="{{ route('projects.show', $project) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">{{ $project->name }}</a>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ ucfirst($project->status) }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ request('trashed') ? $project->deleted_at->format('Y-m-d') : $project->due_date }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">{{ $project->owner->name }}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                            @if (request('trashed'))
                                @can('restore', $project)
                                    <form action="{{ route('projects.restore', $project->id) }}" method="POST" class="inline-block">
                                        @csrf
                                        <button class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-300">Restore</button>
                                    </form>
                                @endcan
                                @can('forceDelete', $project)
                                    <form action="{{ route('projects.force-delete', $project->id) }}" method="POST" class="inline-block">
                                        @csrf @method('DELETE')
                                        <button class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" onclick="return confirm('Permanently delete project? This cannot be undone.')">Delete Permanently</button>
                                    </form>
                                @endcan
                            @else
                                @can('update', $project)
                                    <a href="{{ route('projects.edit', $project) }}" class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-300">Edit</a>
                                @endcan
                                @can('delete', $project)
                                    <form action="{{ route('projects.destroy', $project) }}" method="POST" class="inline-block">
                                        @csrf @method('DELETE')
                                        <button class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300" onclick="return confirm('Move project to trash?')">Trash</button>
                                    </form>
                                @endcan
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No projects found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $projects->withQueryString()->links() }}
    </div>
</div>
</x-layouts.app>
