
<div class="border border-gray-200 dark:border-gray-700 rounded-md p-4 bg-gray-50 dark:bg-gray-800/50">
    <h6 class="font-semibold text-gray-700 dark:text-gray-300 mb-3">Attachments</h6>

    @if($attachments->isNotEmpty())
        <ul class="space-y-2">
            @foreach($attachments as $file)
                <li class="flex items-center justify-between text-sm">
                    <div class="flex items-center space-x-2">
                        <svg class="h-4 w-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.122 2.122l7.81-7.81" />
                        </svg>
                        <a href="{{ route('attachments.download', $file) }}" class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">{{ $file->original_name }}</a>
                    </div>

                    @can('delete', $file)
                        <form action="{{ route('attachments.destroy', $file) }}" method="POST" class="inline-block">
                            @csrf @method('DELETE')
                            <button class="text-red-500 hover:text-red-700 dark:text-red-400 dark:hover:text-red-300 font-medium" onclick="return confirm('Delete attachment?')">Delete</button>
                        </form>
                    @endcan
                </li>
            @endforeach
        </ul>
    @endif

    @can('update', $task)
        <form action="{{ route('attachments.store') }}" method="POST" enctype="multipart/form-data" class="mt-4 flex items-center space-x-3">
            @csrf
            <input hidden name="task_id" value="{{ $task->id }}">
            <input type="file" name="file" required
                   class="block w-full text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 dark:text-gray-400 focus:outline-none dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400
                          file:mr-4 file:py-2 file:px-4
                          file:rounded-l-lg file:border-0
                          file:text-sm file:font-semibold
                          file:bg-indigo-50 file:text-indigo-700
                          hover:file:bg-indigo-100
                          dark:file:bg-indigo-900/50 dark:file:text-indigo-300 dark:hover:file:bg-indigo-900">
            <button type="submit" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                Upload
            </button>
        </form>
    @endcan
</div>
