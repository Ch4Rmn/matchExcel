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

    // protected function romanize($mm)
    // {
    //     return str_replace(
    //         ['မောင်', 'မြင့်', 'သူ', 'အောင်','လင်း'],
    //         ['mg', 'myint', 'thu', 'aung','lin'],
    //         strtolower($mm)
    //     );
    // }

    protected function romanize($mm)
    {
        $mm_words = [
            'မောင်' => 'mg',
            'မြင့်' => 'myint',
            'အောင်' => 'aung',
            'သူ' => 'thu',
            'လင်း' => 'lin',
            'စိုး' => 'soe',
            'မယ်' => 'me',
            'မင်း' => 'min',
            'နိုင်' => 'naing',
            'သိန်း' => 'thein',
            'ကို' => 'ko',
            'ချစ်' => 'chit',
            'စံ' => 'san',
            'ဇင်' => 'zin',
            'ကျော်' => 'kyaw',
            'သန်း' => 'than',
            'သက်' => 'thet',
            'ဇော်' => 'zaw',
            'ထွန်း' => 'htun',
            'သစ်' => 'thit',
            'မမ' => 'ma ma',
            'ထက်' => 'htet',
            'တင်' => 'tin',
            'ဦး' => 'u',
            'ဥဒေါ်' => 'daw',
            'တော်' => 'taw',
            'ကောင်း' => 'kaung',
            'မင်း' => 'min',
            'မေ' => 'may',
            'ထွဋ်' => 'htut',
            'ညို' => 'nyo',
            'အိမ့်' => 'ein',
            'ပွင့်' => 'pwint',
            'ဝင်း' => 'win',
            'သော်' => 'thaw',
            'မုန်း' => 'mone',
            'ခင်' => 'khin',
            'ချို' => 'cho',
            'စွမ်း' => 'swann',
            'စည်' => 'si',
            'ကြည်' => 'kyi',
            'မြ' => 'mya',
            'မော်' => 'maw',
            'ဆု' => 'su',
            'ယမင်း' => 'yamin',
            'အေး' => 'aye',
            'မြင့်' => 'myint',
            'ဉာဏ်' => 'nyan',
            'ညွန့်' => 'nyunt',
            'ပင်' => 'pin',
            'ဇင်မြင့်' => 'zin myint',
            'လှ' => 'hla',
            'အိမ်' => 'ein',
            'ဝေ' => 'way',
            'ဦး' => 'u',
            'မောင်မောင်' => 'mg mg',
            // ... (တခြားထပ်ထည့်နိုင်သမျှ)
        ];

        return str_replace(array_keys($mm_words), array_values($mm_words), strtolower($mm));
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
