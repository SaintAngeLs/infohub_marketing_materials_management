@extends('layouts.app')

@section('content')
    <div class="max-w-9xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white dark:bg-gray-100 overflow-hidden shadow-sm sm:rounded-lg">
            <div class="p-6 text-gray-900 dark:text-gray-900">
                <p class="content-tab-name">
                    {{ __('Menu') }} /
                    @if(isset($selectedMenuItem))
                        <span>{{ $selectedMenuItem->name }}</span>
                    @endif
                </p>
                @if(isset($selectedMenuItem))
                    <h3>Files for {{ $selectedMenuItem->name }}</h3>
                    <form id="download-form" method="POST" action="{{ route('files.downloadMultiple') }}">
                        @csrf
                        <table class="table">
                            <thead>
                            <tr>
                                <th><input type="checkbox" id="select-all" /></th>
                                <th>Name</th>
                                <th>Type</th>
                                <th>Size</th>
                                <th>Updated</th>
                                <th>Download</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse ($selectedMenuItem->files as $file)
                                <tr>
                                    <td><input type="checkbox" name="files[]" value="{{ $file->id }}"></td>
                                    <td>{{ $file->name }}</td>
                                    <td>{{ $file->extension }}</td>
                                    <td>{{ \App\Helpers\FormatBytes::formatBytes($file->weight) }}</td>
                                    <td>{{ $file->updated_at->format('d.m.Y H:i:s') }}</td>
                                    <td><a href="{{ route('files.download', $file->id) }}">Download</a></td>
                                </tr>
                            @empty
                                <tr><td colspan="6">No files available</td></tr>
                            @endforelse
                            </tbody>
                        </table>
                        <button type="submit" onclick="downloadSelectedFiles()">Download Selected</button>
                    </form>
                @else
                    <p>No menu item selected or does not exist.</p>
                @endif
            </div>
        </div>
    </div>

    <script>
        document.getElementById('select-all').addEventListener('click', function(event) {
            var isChecked = event.target.checked;
            var checkboxes = document.querySelectorAll('#download-form input[type="checkbox"][name="files[]"]');
            checkboxes.forEach(function(checkbox) {
                checkbox.checked = isChecked;
            });
        });

        function downloadSelectedFiles() {
            var form = document.getElementById('download-form');
            var checkboxes = form.querySelectorAll('input[type="checkbox"][name="files[]"]:checked');
            if (checkboxes.length > 0) {
                form.submit();
            } else {
                alert('Please select at least one file to download.');
            }
        }
    </script>
@endsection
