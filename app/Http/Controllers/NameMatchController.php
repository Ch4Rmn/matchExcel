<?php

namespace App\Http\Controllers;

use Rabbit;
use Illuminate\Http\Request;
use App\Exports\MatchedExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Session;

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
        // Debugging line to check the contents of the files
        // foreach ($engNames as $eRow) {
        //     $eng = strtolower($eRow[0] ?? '');
        //     // dd($eng);
        //     if (empty($eng)) continue;

        //     foreach ($mmNames as $mRow) {
        //         $mm = $mRow[0] ?? '';
        //         if (empty($mm)) continue;

        //         $roman = $this->romanize($mm);
        //         $match = $this->matchPercent($eng, $roman);

        //         if ($match >= 60) {
        //             $results[] = [
        //                 'eng' => $eng,
        //                 'mm' => $mm,
        //                 'roman' => $roman,
        //                 'match' => $match,
        //             ];
        //         }
        //     }
        // }
        foreach ($engNames as $eRow) {
            $eng = strtolower($eRow[0] ?? '');
            Rabbit::zg2uni($eng);
            if (empty($eng)) continue;

            foreach ($mmNames as $mRow) {
                $mm = strtolower($mRow[0] ?? '');
                Rabbit::zg2uni($mm);
                if (empty($mm)) continue;

                // Direct Levenshtein similarity
                // $distance = levenshtein($eng, $mm);
                // $maxLen = max(mb_strlen($eng), mb_strlen($mm));
                // $ratio = $maxLen > 0 ? (1 - $distance / $maxLen) * 100 : 0;

                // Direct similar_text similarity
                similar_text($eng, $mm, $ratio);


                if ($ratio >= 50) {
                    $results[] = [
                        'eng' => $eng,
                        'mm' => $mm,
                        'similarity' => round($ratio, 2),
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
}
// protected function romanize($mm)
// {
//     return str_replace(
//         ['မောင်', 'မြင့်', 'သူ', 'အောင်','လင်း'],
//         ['mg', 'myint', 'thu', 'aung','lin'],
//         strtolower($mm)
//     );
// }

//     protected function romanize($mm)
//     {
//         $mm_words = [
//             'မန်' => 'မ',
//             'မန့်' => 'မန်း',
//             'မျက်' => 'မက်',
//             'ကြီး' => 'ကျီး',
//             'ပင်' => 'ပင်',
//         ];
//         return str_replace(array_keys($mm_words), array_values($mm_words), strtolower($mm));
//     }


//     // ...existing code...

//     // protected function matchPercent($base, $target)
//     // {
//     //     $distance = levenshtein($base, $target);
//     //     $maxLen = max(mb_strlen($base), mb_strlen($target));
//     //     $ratio = $maxLen > 0 ? (1 - $distance / $maxLen) * 100 : 0;
//     //     return round($ratio, 2);
//     // }

//     // ...existing code...
//     protected function matchPercent($a, $b)
//     {
//         similar_text($a, $b, $percent);
//         return round($percent, 2);
//     }
// }

/**
 * Show the form for creating a new resource.
 */
