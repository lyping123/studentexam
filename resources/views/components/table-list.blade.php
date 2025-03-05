<table class="table table-striped table-bordered">
    <thead class="table-dark">
        <tr>
            @foreach ($headers as $header)
                <th>{{ $header }}</th>
            @endforeach
            @if (!empty($actions))
                <th>Actions</th>
            @endif
        </tr>
    </thead>
    <tbody>
        
        @forelse ($data as $row)
            <tr>
                @foreach ($headers as $key => $header)
                    <td>{{ $row[$key] ?? 'N/A' }}</td>
                @endforeach
                @if (!empty($actions))
                    <td>
                        @foreach ($actions as $action)
                            <a href="{{ route($action['route'], $row['id']) }}" class="btn btn-{{ $action['class'] }} btn-sm">
                                {{ $action['label'] }}
                            </a>
                        @endforeach
                    </td>
                @endif
            </tr>
        @empty
            <tr>
                <td colspan="{{ count($headers) + (empty($actions) ? 0 : 1) }}" class="text-center">No records found.</td>
            </tr>
        @endforelse
    </tbody>
</table>
