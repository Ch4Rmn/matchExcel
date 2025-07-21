<!DOCTYPE html>
<html>

<head>
    <title>Name Match</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        {{-- <h3>Name Matching</h3> --}}
        <form action="{{ route('name.match.compare') }}" method="POST" enctype="multipart/form-data">
            @csrf
            {{-- <div class="mb-3">
                <label>English Name Excel</label>
                <input type="file" name="eng_file" class="form-control" required>
            </div> --}}
            <div class="mb-3">
                <label>Myanmar Name Excel</label>
                <input type="file" name="eng_file" class="form-control" required>
            </div>
            <div class="mb-3">
                <label>Burmese Name Excel</label>
                <input type="file" name="mm_file" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Compare</button>
        </form>

        @if (session('results'))
            <h4 class="mt-4">Results</h4>
            <a href="{{ route('name.match.export') }}" class="btn btn-success mb-2">Export XLSX</a>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Base Burmese Name</th>
                        <th>Match Burmese Name</th>
                        {{-- <th>Romanized</th> --}}
                        <th>Match %</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach (session('results') as $row)
                        <tr>
                            <td>{{ $row['eng'] }}</td>
                            <td>{{ $row['mm'] }}</td>
                            {{-- <td>{{ $row['roman'] }}</td> --}}
                            <td>{{ $row['similarity'] }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</body>

</html>
