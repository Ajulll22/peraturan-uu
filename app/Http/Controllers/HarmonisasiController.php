<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use Smalot\PdfParser\Parser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Models\Category;
use App\Models\Pasal;
use App\Models\Ruu;
use App\Models\Ruu_pasal;
use Sastrawi\Stemmer\StemmerFactory;

class HarmonisasiController extends Controller
{
    use NavigationList;
    use PrepareArchive;

    public function index()
    {
        // PAGE SETUP
        $pageTitle = 'Harmonisasi';
        $active = 'Harmonisasi';

        return view('pages.harmonisasi.index', [
            'user' => Auth::user(),
            'pageTitle' => $pageTitle,
            'active' => $active,
            'navs' => $this->NavigationList(),
        ]);
    }

    public function store_new(Request $request)
    {
        // VALIDATE REQUEST INPUT
        $request->validate([
            'uu' => 'required',
            'tentang' => 'required',
            'keyword' => 'required',
        ]);

        // try {
        $fileName = '';
        if ($request->fromFileUpload == 'false') {
            // SAVE ARCHIVE FILE PDF
            $file = $request->file('arsip');
            $fileName = 'Harmonisasi-' . time() . '.' . $file->extension();
        } else {
            // COPY TEMP FILE TO A NEW FOLDER
            $oldFile = public_path() . '\assets\hitung\temp-harmonisasi.pdf';
            $newFile = 'Harmonisasi-' . time() . '.pdf';
            if (!copy($oldFile, public_path('\assets\pdf\\' . $newFile))) {
                return redirect(route('harmonisasi'))->with('failed', 'Copy file failed!');
            }
            $fileName = $newFile;
        }

        $ruu = Ruu::create([
            'judul_ruu' => $request->uu,
            'tentang_ruu' => $request->tentang,
            'keyword_ruu' => $request->keyword,
            'file_ruu' => $fileName,
        ]);

        // PROCESS PASAL AYAT
        $pasalUpload = [];
        foreach ($request->all() as $key => $item) {
            if (str_contains($key, 'pasal')) {
                $data = [];
                $data['id_ruu'] = $ruu->id_ruu;
                $data['section_ruu'] = str_replace("_", ' ', $key);
                $data['content_ruu'] = str_replace("\r\n", '<br>', $item);;
                array_push($pasalUpload, $data);
            }
        }
        // INSERT PASAL RECORD
        foreach ($pasalUpload as $item) {
            Ruu_pasal::create(
                $item
            );
        }
        // dd($pasalToInsert);

        // } catch (Exception $e) {
        //     dd($e);
        //     return redirect(route('archive.index'))->with('failed', 'Something wrong!');
        // }
        return redirect(route('harmonisasi.result'))->with('success', 'Data RUU Sedang Diproses!');
    }

    public function store(Request $request)
    {
        $image = $request->file('file');

        $imageName = 'pembanding' . '.' . $image->extension();
        $image->move(public_path('assets/hitung'), $imageName);

        return response()->json(['success' => $imageName]);
    }

    public function result()
    {
        // PAGE SETUP
        $pageTitle = 'Hasil Harmonisasi';
        $active = 'Harmonisasi';

        if (!file_exists(public_path('assets\hitung\pembanding.pdf'))) {
            return redirect(route('harmonisasi.index'))->with('failed', 'Belum terdapat file pembanding');
        }

        return view('pages.harmonisasi.result', [
            'user' => Auth::user(),
            'pageTitle' => $pageTitle,
            'active' => $active,
            'navs' => $this->NavigationList(),
        ]);
    }

    public function resultData()
    {
        $url = 'http://localhost:8080/v1/harmonisasi/keyword';
        $data = json_decode(file_get_contents($url))->value;

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('file_arsip', function ($row) {
                return view('components.data-table.harmonisasi-file', compact(['row']));
            })
            ->editColumn('presentase', function ($row) {
                return $row->presentase . '%';
            })
            ->make(true);
    }

    public function harmonisasiDetail(Request $request)
    {
        $url = 'http://localhost:5000/harmonisasi/wordvec_detail/' . $request->id_tbl_uu;
        $data = json_decode(file_get_contents($url))->values;
        // dd($data);
        // PAGE SETUP
        $pageTitle = 'Harmonisasi Detail';
        $active = 'Harmonisasi';
        $breadCrumbs = [
            'bx-icon' => 'bx bx-notepad',
            'list' => [
                ['title' => 'Arsip', 'url' => route('archive.index')],
            ]
        ];
        $pembanding = Ruu::select("*")->orderBy("id_ruu", "desc")->first();
        $archive = Archive::select("id_tbl_uu", "uu", "tentang")->find($request->id_tbl_uu);

        return view('pages.harmonisasi.show', [
            'user' => Auth::user(),
            'pageTitle' => $pageTitle,
            'active' => $active,
            'breadCrumbs' => $breadCrumbs,
            'navs' => $this->NavigationList(),
            'archive' => $archive,
            'pembanding' => $pembanding,
            'pasals' => $data,
        ]);
    }

    public function resultDetail(Request $request)
    {
        // PAGE SETUP
        $url = 'http://localhost:5000/v1/harmonisasi/show/' . $request->id_tbl_uu;
        $data = json_decode(file_get_contents($url))->values;
        // dd($data);
        // PAGE SETUP
        $pageTitle = 'Harmonisasi Detail';
        $active = 'Harmonisasi';
        $breadCrumbs = [
            'bx-icon' => 'bx bx-notepad',
            'list' => [
                ['title' => 'Arsip', 'url' => route('archive.index')],
            ]
        ];
        $pembanding = Ruu::select("*")->orderBy("id_ruu", "desc")->first();
        $Ruu = Ruu_pasal::select("*")->where('id_ruu', $pembanding->id_ruu)->get();
        $archive = Archive::select("id_tbl_uu", "uu", "tentang")->find($request->id_tbl_uu);

        return view('pages.harmonisasi.detail', [
            'user' => Auth::user(),
            'pageTitle' => $pageTitle,
            'active' => $active,
            'breadCrumbs' => $breadCrumbs,
            'navs' => $this->NavigationList(),
            'archive' => $archive,
            'pembanding' => $pembanding,
            'ruu' => $Ruu,
            'pasals' => $data,
        ]);
    }

    public function fileProcess(Request $request)
    {
        // dd(asset('assets/hitung/temp-archive.pdf'));
        // dd('this is soter');
        // $file = $request->archive;

        // $request->validate([
        //     'archive' => 'required|mimes:pdf',
        // ]);

        // use of pdf parser to read content from pdf 
        // $fileName = $file->getClientOriginalName();
        $pdfPath = public_path() . '\assets\hitung\temp-harmonisasi.pdf';
        $pdfParser = new Parser();
        $pdf = $pdfParser->parseFile($pdfPath);
        // get the pdf text
        $content = $pdf->getText();
        // make all char Lowercase
        // $contentLowerCase = strtolower($content);
        // remove tab
        $content = str_replace("\t", '', $content);
        // explode by \n
        $arrContent = explode("\n", $content);

        // REQURED INFORMATION
        $indexedPasal = [];

        // TRIM ALL SPACES INSIDE ARRAY DOCUMENT
        for ($i = 0; $i < count($arrContent); $i++) {
            $arrContent[$i] = preg_replace('/^\p{Z}+|\p{Z}+$/u', '', $arrContent[$i]);
            $arrContent[$i] = preg_replace('/\h+/u', ' ', $arrContent[$i]);
        }

        // REMOVE UNNECESSARY ARRAY
        $tempContent = '';
        for ($i = count($arrContent) - 1; $i >= 0; $i--) {
            if (strlen($arrContent[$i]) == 0) {
                array_splice($arrContent, $i, 1);
                continue;
            }
            if ($arrContent[$i] == $tempContent) {
                array_splice($arrContent, $i, 2);
                $tempContent = '';
                continue;
            }

            if (str_contains($arrContent[$i], ". . .")) {
                array_splice($arrContent, $i, 1);
                continue;
            }
            $tempContent = $arrContent[$i];
        }

        // CHANGE FONT TO LOWERCASE
        $arrContentLowerCase = [];
        $i = 0;
        foreach ($arrContent as $content) {
            $arrContentLowerCase[$i] = strtolower($content);
            $i++;
        }

        // dd($arrContent, $arrContentLowerCase);

        $i = 0;
        $listPasal = [];
        foreach ($arrContentLowerCase as $idx => $content) {
            // $content = preg_replace('/^\p{Z}+|\p{Z}+$/u', '', $content);
            if (in_array($content, $listPasal)) {
                break;
            }

            if (strlen($content) < 15) {
                if (str_contains($content, 'pasal')) {
                    $indexedPasal[$i]['index'] = $idx;
                    $indexedPasal[$i]['content'] = $content;
                    array_push($listPasal, $content);
                    $i++;
                }
            }
        }
        //dd($indexedPasal);

        $pasalContent = [];
        $i = 0;
        foreach ($indexedPasal as $idx => $content) {
            // EXTRACT EVERYTHING EXCEPT THE LAST INDEX
            if ($idx < count($indexedPasal) - 1) {
                $pasalContent[$idx]['title'] = $content['content'];
                $pasalContent[$idx]['content'] = array_slice($arrContent, $content['index'] + 1, $indexedPasal[$idx + 1]['index'] - $content['index'] - 1);
            }
            // EXTRAXT THE LAST INDEX
            if ($idx == count($indexedPasal) - 1) {
                $pasalContent[$idx]['title'] = $content['content'];
                $pasalContent[$idx]['content'][0] = 'Undang-Undang ini mulai berlaku pada saat diundangkan';
            }
        }
        //dd($pasalContent);

        // DIVIDE THE PASAL CONTENT BASED ON AYAT
        $pasalAyat = [];
        $i = 0;
        foreach ($pasalContent as $pasal) {
            $pasalAyat[$i]['title'] = $pasal['title'];
            $pasalAyat[$i]['content'] = [];
            $currentAyat = 1;
            $foundBAB = false;
            foreach ($pasal['content'] as $ayat) {
                // REMOVE UNNECESARY STRING, EX: -8-, -9-, BAB, and BAB TITLE
                if ((substr($ayat, 0, 3) == 'BAB') || ($foundBAB) || (substr($ayat, 0, 9) == 'Bagian Ke') || (substr($ayat, 0, 8) == 'Paragraf')) {
                    $foundBAB = true;
                } else {
                    // CHECK THE FIRST WORD OF EACH LINE TO FIND A AYAT FORMAT, EX: (1), (2), (3)
                    $firstWord = explode(' ', $ayat)[0];
                    $firstWordLength = strlen($firstWord);
                    $firstChar = substr($firstWord, 0, 1);
                    $midleChar = substr($firstWord, 1, $firstWordLength - 2);
                    $lastChar = substr($firstWord, -1);
                    $arrayLength = count($pasalAyat[$i]['content']);
                    // IF THE FIRST WORD IS IN AYAT FORMAT, THEN PUSH AS NEW AYAT
                    if ($firstChar == '(' && $lastChar == ')') {
                        if (is_numeric($midleChar)) {
                            if (empty($pasalAyat[$i]['content'])) {
                                array_push($pasalAyat[$i]['content'], $ayat);
                            } else {
                                if ($midleChar == $currentAyat + 1) {
                                    array_push($pasalAyat[$i]['content'], $ayat);
                                    $currentAyat++;
                                    $foundBAB = false;
                                } else {
                                    // ADD NEW LINE IF ITS A NUMBER
                                    $divider = $firstWordLength <= 4 && $lastChar == '.' ? "\n" : ' ';
                                    $pasalAyat[$i]['content'][$arrayLength - 1] .= $divider . $ayat;
                                }
                            }
                        } else {
                            // ADD NEW LINE IF ITS A NUMBER
                            $divider = $firstWordLength <= 4 && $lastChar == '.' ? "\n" : ' ';
                            $pasalAyat[$i]['content'][$arrayLength - 1] .= $divider . $ayat;
                        }
                    } else {
                        if (empty($pasalAyat[$i]['content'])) {
                            array_push($pasalAyat[$i]['content'], $ayat);
                        } else {
                            // ADD NEW LINE IF ITS A NUMBER
                            $divider = $firstWordLength <= 4 && $lastChar == '.' ? "\n" : ' ';
                            $pasalAyat[$i]['content'][$arrayLength - 1] .= $divider . $ayat;
                        }
                    }
                }
            }
            $i++;
        }
        //dd($pasalAyat);

        // PAGE SETUP
        $pageTitle = 'Konfirmasi Arsip';
        $active = 'Harmonisasi';
        $breadCrumbs = [
            'bx-icon' => 'bx bx-notepad',
            'list' => [
                ['title' => 'Harmonisasi', 'url' => route('harmonisasi.index')],
                ['title' => 'Arsip Baru', 'url' => route('harmonisasi.create')],
                ['title' => 'Upload', 'url' => ''],
                ['title' => 'Konfirmasi', 'url' => ''],
            ]

        ];
        // GET DATA
        $categories = Category::all();

        return view('pages.harmonisasi.create', [
            'user' => Auth::user(),
            'pageTitle' => $pageTitle,
            'active' => $active,
            'breadCrumbs' => $breadCrumbs,
            'navs' => $this->NavigationList(),
            'categories' => $categories,
            'result' => $pasalAyat,
            'fromFileUpload' => true,
        ]);
    }

    public function fileStore(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:pdf',
        ]);
        $image = $request->file('file');

        $imageName = 'temp-harmonisasi' . '.' . $image->extension();
        $image->move(public_path('assets/hitung'), $imageName);

        return response()->json(['success' => $imageName]);
    }

    public function show(Request $request)
    {
        $ruu = Ruu::select("*")->orderBy("id_ruu", "desc")->first();
        $pasals = Pasal::with(['uu'])
            ->where('id_tbl_uu', $request->id_tbl_uu)
            ->where(function ($query) {
                $query->where('uud_id', 'LIKE', '%pasal%')
                    ->orWhere('uud_id', 'LIKE', '%ayat%');
            })->get();

        $simplePasal = [];
        $i = 0;
        foreach ($pasals as $item) {
            $simplePasal[$i]['id'] = $item->id;
            $simplePasal[$i]['uu'] = $item->uu->uu;
            $simplePasal[$i]['tentang'] = $item->uu->tentang;
            $simplePasal[$i]['uud_id'] = $item->uud_id;
            $simplePasal[$i]['uud_content'] = $this->findSimilarity($ruu->keyword_ruu, $item->uud_content);
            // $item->uud_content .= $this->findSimilarity($request->theme, $item->uud_content);
            $i++;
        }

        // dd($simplePasal);
        $pageTitle = 'Harmonisasi Detail';
        $active = 'Harmonisasi';
        $breadCrumbs = [
            'bx-icon' => 'bx bx-notepad',
            'list' => [
                ['title' => 'Arsip', 'url' => route('archive.index')],
            ]
        ];
        $pembanding = Ruu::select("*")->orderBy("id_ruu", "desc")->first();
        $Ruu = Ruu_pasal::select("*")->where('id_ruu', $pembanding->id_ruu)->get();
        $archive = Archive::select("id_tbl_uu", "uu", "tentang")->find($request->id_tbl_uu);

        return view('pages.harmonisasi.show-detail', [
            'user' => Auth::user(),
            'pageTitle' => $pageTitle,
            'active' => $active,
            'breadCrumbs' => $breadCrumbs,
            'navs' => $this->NavigationList(),
            'archive' => $archive,
            'pembanding' => $pembanding,
            'ruu' => $Ruu,
            'simplePasal' => $simplePasal,
            'highlight' => $ruu->keyword_ruu,
        ]);
    }

    private function findSimilarity($theme, $str)
    {
        // STEM THE THEM INPUT
        $stemmerFactory = new StemmerFactory;
        $stemmer = $stemmerFactory->createStemmer();
        $stemmingQuery = $stemmer->stem($theme);
        // TRANSFORM QUERY INTO ARRAY
        $query = explode(' ', $stemmingQuery);

        // GET ARCHIVE UU FILE PATH
        $newArsipPembanding1 = $str;

        $newArsipPembanding1 = str_ireplace("\n", "<br>", $newArsipPembanding1);

        $stemminghtml = explode("<br>", $newArsipPembanding1);

        foreach ($stemminghtml as $key => $value) {
            $stemminghtml[$key] = explode(" ", $value);
        }
        $count = 0;
        foreach ($stemminghtml as $key => $value) {
            foreach ($value as $key1 => $value1) {
                if (
                    in_array($stemmer->stem(strtolower($stemminghtml[$key][$key1])), $query)
                ) {
                    $count++;
                    $stemminghtml[$key][$key1] = '<span style="background: yellow">' . $stemminghtml[$key][$key1] . '</span>';
                }
            }
        }

        foreach ($stemminghtml as $key => $value) {
            $stemminghtmlnew[$key] = implode(' ', $value);
        }
        $stemminghtmlnew = implode("<br>", $stemminghtmlnew);

        // return $stemminghtmlnew;
        return [
            'count' => $count,
            'content' => $stemminghtmlnew,
        ];
    }


}
