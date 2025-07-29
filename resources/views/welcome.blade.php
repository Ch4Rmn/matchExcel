<!DOCTYPE html>
<html>

<head>
    <title>Name Match</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">

        <a href="{{ asset('2023-24 eo.xlsx') }}">Download Myanmar Name Excel</a>
        <br>
        <a href="{{ asset('POI.xlsx') }}">Download Name Excel</a>
        <hr>

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
                <label>Burmese Myanmar Name Excel</label>
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
                        <th>DPS Name</th>
                        <th>EO Name</th>
                        <th>similarity</th>
                        {{-- <th>Romanized</th> --}}
                        <th>လုပ်ငန်းအမျိုးအစား</th>
                        <th>လိုင်စင်အမှတ်</th>
                        <th>လုပ်ငန်းရှင်အမည် နိုင်ငံသားစီစစ်ရေးကတ်ပြားအမှတ်</th>
                        <th>လုပ်ငန်းလိပ်စာ</th>
                        <th>၂၀၂၁-၂၂ကြားကာလ(၆)လနှုန်း</th>
                        <th>၂၀၂၂-.၂၀၂၃နှုန်း</th>
                        <th>၂၀၂၃-.၂၀၂၄အဆိုပြုနှုန်း</th>
                        <th>နှုန်းတိုး</th>
                        <th>၂၀၂၃-၂၄ခရိုင်အတည်ပြုနှုန်း</th>
                        <th>မှတ်ချက်</th>
                        {{-- <td>{{ $row['လုပ်ငန်းအမျိုးအစား'] }}</td>
                            <td>{{ $row['လိုင်စင်အမှတ်'] }}</td>
                            <td>{{ $row['လုပ်ငန်းရှင်အမည် နိုင်ငံသားစီစစ်ရေးကတ်ပြားအမှတ်'] }}</td>
                            <td>{{ $row['လုပ်ငန်းလိပ်စာ'] }}</td>
                            <td>{{ $row['၂၀၂၁-၂၂ကြားကာလ(၆)လနှုန်း'] }}</td>
                            <td>{{ $row['၂၀၂၂-.၂၀၂၃နှုန်း'] }}</td>
                            <td>{{ $row['၂၀၂၃-.၂၀၂၄အဆိုပြုနှုန်း'] }}</td>
                            <td>{{ $row['နှုန်းတိုး'] }}</td>
                            <td>{{ $row['၂၀၂၃-၂၄ခရိုင်အတည်ပြုနှုန်း'] }}</td>
                            <td>{{ $row['မှတ်ချက်'] }}</td> --}}
                    </tr>
                </thead>
                <tbody>
                    @foreach (session('results') as $row)
                        <tr>
                            <td>{{ $row['eng'] }}</td>
                            <td>{{ $row['mm'] }}</td>
                            <td>{{ $row['similarity'] }}</td>
                            <td>{{ $row['လုပ်ငန်းအမျိုးအစား'] }}</td>
                            <td>{{ $row['လိုင်စင်အမှတ်'] }}</td>
                            <td>{{ $row['လုပ်ငန်းရှင်အမည် နိုင်ငံသားစီစစ်ရေးကတ်ပြားအမှတ်'] }}</td>
                            <td>{{ $row['လုပ်ငန်းလိပ်စာ'] }}</td>
                            <td>{{ $row['၂၀၂၁-၂၂ကြားကာလ(၆)လနှုန်း'] }}</td>
                            <td>{{ $row['၂၀၂၂-.၂၀၂၃နှုန်း'] }}</td>
                            <td>{{ $row['၂၀၂၃-.၂၀၂၄အဆိုပြုနှုန်း'] }}</td>
                            <td>{{ $row['နှုန်းတိုး'] }}</td>
                            <td>{{ $row['၂၀၂၃-၂၄ခရိုင်အတည်ပြုနှုန်း'] }}</td>
                            <td>{{ $row['မှတ်ချက်'] }}</td>
                            {{-- <td>{{ $row['full_row'] }}</td> --}}
                            {{-- 'eng' => $eng,
                        'mm'  => $mmUni,
                        'လုပ်ငန်းအမျိုးအစား' => $mRow[2],
                        'လိုင်စင်အမှတ်' => $mRow[3],
                        'လုပ်ငန်းရှင်အမည် နိုင်ငံသားစီစစ်ရေးကတ်ပြားအမှတ်' => $mRow[4],
                        'လုပ်ငန်းလိပ်စာ' => $mRow[5],
                        '၂၀၂၁-၂၂ကြားကာလ(၆)လနှုန်း' => $mRow[6],
                        '၂၀၂၂-.၂၀၂၃နှုန်း' => $mRow[7],
                        '၂၀၂၃-.၂၀၂၄အဆိုပြုနှုန်း' => $mRow[8],
                        'နှုန်းတိုး' => $mRow[9],
                        '၂၀၂၃-၂၄ခရိုင်အတည်ပြုနှုန်း' => $mRow[10],
                        'မှတ်ချက်' => $mRow[11],

                        // 'business' => $
                        'similarity' => $similarity, --}}
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
</body>

</html>
