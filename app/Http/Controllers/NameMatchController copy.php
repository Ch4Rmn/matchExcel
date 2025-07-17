<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;
use App\Exports\MatchedExport;

class NameMatchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('welcome');
    }

    public function compare(Request $request)
    {
        $engNames = Excel::toArray([], $request->file('eng_file'))[0];
        $mmNames = Excel::toArray([], $request->file('mm_file'))[0];
        // dd($engNames, $mmNames); // Debugging line to check the contents of the files
        $results = [];

        foreach ($engNames as $eRow) {
            $eng = strtolower($eRow[0] ?? '');
            if (empty($eng)) continue;

            foreach ($mmNames as $mRow) {
                $mm = $mRow[0] ?? '';
                if (empty($mm)) continue;

                $roman = $this->romanize($mm);
                $match = $this->matchPercent($eng, $roman);

                if ($match >= 60) {
                    $results[] = [
                        'eng' => $eng,
                        'mm' => $mm,
                        'roman' => $roman,
                        'match' => $match,
                    ];
                }
            }
        }

        Session::put('matched_results', $results);
        return redirect()->back()->with('results', $results);
    }

    public function export()
    {
        $data = Session::get('matched_results', []);
        return Excel::download(new MatchedExport($data), 'matched_results.xlsx');
    }

    protected function romanize($mm)
    {
        return str_replace(
            ['မောင်', 'မြင့်', 'သူ', 'အောင်'],
            ['mg', 'myint', 'thu', 'aung'],
            strtolower($mm)
        );
    }

    protected function matchPercent($a, $b)
    {
        similar_text($a, $b, $percent);
        return round($percent, 2);
    }
}

/**
 * Show the form for creating a new resource.
 */
