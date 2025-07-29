<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exports\MatchedExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;
use Rabbit;

class NameMatchController extends Controller
{
    public function index()
    {
        return view('welcome');
    }

    public function compare(Request $request)
    {
        $engSheet = Excel::toArray([], $request->file('eng_file'))[0];
        $mmSheet  = Excel::toArray([], $request->file('mm_file'))[0];

        $results = [];

        foreach ($engSheet as $eRow) {
            $eng = strtolower(trim($eRow[1] ?? ''));

            if (empty($eng)) continue;

            // Convert English to Unicode just in case (if needed)
            // $engUni = Rabbit::zg2uni($eng);

            foreach ($mmSheet as $mRow) {
                $mm = strtolower(trim($mRow[8] ?? ''));

                if (empty($mm)) continue;

                $mmUni = Rabbit::zg2uni($mm);

                $similarity = $this->matchPercent($eng, $mmUni);

                if ($similarity >= 70) {
                    $results[] = [
                        'eng' => $eng,
                        'mm'  => $mmUni,
                        'similarity' => $similarity,
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

    protected function matchPercent($a, $b)
    {
        similar_text($a, $b, $percent);
        return round($percent, 2);
    }
}
