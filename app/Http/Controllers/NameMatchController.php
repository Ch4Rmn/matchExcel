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
        // dd()
        // Excel::import(new )
        $engSheet = Excel::toArray([], $request->file('eng_file'))[0];
        $mmSheet  = Excel::toArray([], $request->file('mm_file'))[0];

        $results = [];

        foreach ($engSheet as $eRow) {
            // $eng = strtolower(trim($eRow[28,29] ?? ''));
            $eng = strtolower(trim($eRow[1] ?? ''));

            if (empty($eng)) continue;

            // Convert English to Unicode just in case (if needed)
            // $engUni = Rabbit::zg2uni($eng);

            foreach ($mmSheet as $mRow) {
                // dd($mRow[0], $mRow[1], $mRow[2], $mRow[3], $mRow[4], $mRow[5], $mRow[6], $mRow[7],);
                $mm = strtolower(trim($mRow[8] ?? ''));

                if (empty($mm)) continue;

                $mmUni = Rabbit::zg2uni($mm);

                $similarity = $this->matchPercent($eng, $mmUni);

                if ($similarity >= 70) {
                    $results[] = [
                        'eng' => $eng,
                        'mm'  => $mmUni,
                        'လုပ်ငန်းအမျိုးအစား' => $eRow[2],
                        'လိုင်စင်အမှတ်' => $eRow[3],
                        'လုပ်ငန်းရှင်အမည် နိုင်ငံသားစီစစ်ရေးကတ်ပြားအမှတ်' => $eRow[4],
                        'လုပ်ငန်းလိပ်စာ' => $eRow[5],
                        '၂၀၂၁-၂၂ကြားကာလ(၆)လနှုန်း' => $eRow[6],
                        '၂၀၂၂-.၂၀၂၃နှုန်း' => Rabbit::zg2uni($eRow[7]),
                        '၂၀၂၃-.၂၀၂၄အဆိုပြုနှုန်း' => Rabbit::zg2uni($eRow[8]),
                        'နှုန်းတိုး' => $eRow[9],
                        '၂၀၂၃-၂၄ခရိုင်အတည်ပြုနှုန်း' => $eRow[10],
                        'မှတ်ချက်' => $eRow[11],

                        // 'business' => $
                        'similarity' => $similarity,
                        // 'full_row'   => $mRow, // include full mmSheet row

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
