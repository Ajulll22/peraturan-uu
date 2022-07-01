<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use App\Models\Category;
use App\Models\Pasal;
use App\Models\StemmingTable;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Sastrawi\Stemmer\StemmerFactory;
use Smalot\PdfParser\Parser;
use Yajra\DataTables\Contracts\DataTable;
use Yajra\DataTables\Facades\DataTables;
use MPDF;

class DraftController extends Controller
{
    use NavigationList;

    public function index(Request $request)
    {
        // PAGE SETUP
        $pageTitle = 'Drafting';
        $active = 'Drafting';
        $breadCrumbs = [
            'bx-icon' => 'bx bxs-book-content',
            'list' => [
                ['title' => 'Drafting', 'url' => route('archive.index')],
            ]
        ];
        // GET DATA
        $mode = $request->mode;
        $data = [];

        return view('pages.draft', [
            'user' => Auth::user(),
            'pageTitle' => $pageTitle,
            'active' => $active,
            'breadCrumbs' => $breadCrumbs,
            'navs' => $this->NavigationList(),
            'data' => $data,
            'mode' => $mode,
        ]);
    }

    public function calcPasalSimilarity(Request $request)
    {
        $wordvecPasal = Http::post('localhost:5000/wordvecPasal', [
            'kalimat' => $request->theme,
        ]);

        return $wordvecPasal['values'];
    }

    public function hitungCousine(Request $request)
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '2048M');
        $theme = strtolower($request->theme);
        if ($theme == '' || $theme == null) {
            $theme = $request->theme;
            return DataTables::of([])
                ->editColumn('cosSim', 'components.data-table.draft-cosSim')
                ->addColumn('detail', function ($row) use ($theme) {
                    return view('components.data-table.draft-detail', compact(['row', 'theme']));
                })
                ->rawColumns(['detail'])
                ->make(true);
        }
        $dataTF = StemmingTable::get();
        // PROCESS IF dataTF NOT NULL
        if ($dataTF) {
            $stemmerFactory = new StemmerFactory;
            $stemmer = $stemmerFactory->createStemmer();

            // // STEMMING THEME (menghilangkan imbuhan dan akhiran setiap kata pada tema input)
            $stemmingQuery = $stemmer->stem($theme);
            // TRANSFORM QUERY INTO ARRAY
            $queries = explode(' ', $stemmingQuery);
            foreach ($dataTF as $key => $value) {
                $newTF[$key] = json_decode($value->array);
            }

            foreach ($dataTF as $key => $value) {
                foreach ($newTF[$key] as $key1 => $value) {
                    $dataTF[$key]->array = $newTF[$key];
                }
            }

            $count = array();
            foreach ($dataTF as $kk => $value2) {
                $text = $value2->array;
                foreach ($text as $keykey => $value) {
                    if ($text[$keykey] == "html") {
                        unset($text[$keykey]);
                    }
                }
                $count[$kk] = array_count_values($text);
            }

            $query1 = array_count_values($queries);

            foreach ($count as $key => $value) {
                foreach ($value as $key1 => $value1) {
                    foreach ($query1 as $key2 => $value2) {
                        if ($key1 == $key2) {
                            $kataSama[$key][$key2] = $value[$key1];
                            $topCos[$key][$key2] = $value[$key1] * $query1[$key2];
                        }
                    }
                }
            }

            $listData = Archive::with(['category'])->get();
            if (!empty($kataSama) && !empty($topCos)) {
                foreach ($kataSama as $key => $value) {
                    if (!empty($kataSama[$key]) && !empty($topCos[$key])) {
                        $newKataSama[$key] = array_sum($value);
                    }
                }
                foreach ($topCos as $key => $value) {
                    $newTopCos[$key] = array_sum($topCos[$key]);
                }

                foreach ($count as $key => $value) {
                    foreach ($value as $key1 => $value1) {
                        $bottomCos[$key][$key1] = pow($value[$key1], 2);
                    }
                }
                foreach ($bottomCos as $key => $value) {
                    $bottomCos[$key] = sqrt(array_sum($bottomCos[$key]));
                }
                foreach ($query1 as $key => $value) {
                    $bottomQuery[$key] = pow($query1[$key], 2);
                }
                $bottomQuery = sqrt(array_sum($bottomQuery));

                foreach ($bottomCos as $key => $value) {
                    $fixBottomCos[$key] = $value * $bottomQuery;
                }
                foreach ($newTopCos as $key => $value) {
                    $cosSim[$key] = $newTopCos[$key] / $fixBottomCos[$key];
                }

                foreach ($listData as $key => $value) {
                    if (!empty($newKataSama[$key])) {
                        // $data[$key]['id_arsip'] = $listData[$key]->id_arsip;
                        // $data[$key]['judul_arsip'] = $listData[$key]->judul_arsip;
                        // $data[$key]['jenis_arsip'] = $listData[$key]->jenis_arsip;
                        // $data[$key]['kategori'] = $listData[$key]->category ? $listData[$key]->category->nama_kategori : ' ';
                        // $data[$key]['kataSama'] = $newKataSama[$key];
                        // $data[$key]['cosSim'] = $cosSim[$key];

                        $data[$key]['id_arsip'] = $listData[$key]->id_tbl_uu;
                        $data[$key]['judul_arsip'] = $listData[$key]->uu;
                        $data[$key]['jenis_arsip'] = $listData[$key]->tentang;
                        $data[$key]['kategori'] = $listData[$key]->category ? $listData[$key]->category->nama_kategori : ' ';
                        $data[$key]['kataSama'] = $newKataSama[$key];
                        $data[$key]['cosSim'] = $cosSim[$key];
                    } else {
                        // $data[$key]['id_arsip'] = $listData[$key]->id_arsip;
                        // $data[$key]['judul_arsip'] = $listData[$key]->judul_arsip;
                        // $data[$key]['jenis_arsip'] = $listData[$key]->jenis_arsip;
                        // $data[$key]['kategori'] = $listData[$key]->category ? $listData[$key]->category->nama_kategori : ' ';
                        // $data[$key]['kataSama'] = '0';
                        // $data[$key]['cosSim'] = 0;

                        $data[$key]['id_arsip'] = $listData[$key]->id_tbl_uu;
                        $data[$key]['judul_arsip'] = $listData[$key]->uu;
                        $data[$key]['jenis_arsip'] = $listData[$key]->tentang;
                        $data[$key]['kategori'] = $listData[$key]->category ? $listData[$key]->category->nama_kategori : ' ';
                        $data[$key]['kataSama'] = '0';
                        $data[$key]['cosSim'] = 0;
                    }
                }
            } else {
                foreach ($listData as $key => $value) {
                    // $data[$key]['id_arsip'] = $listData[$key]->id_arsip;
                    // $data[$key]['judul_arsip'] = $listData[$key]->judul_arsip;
                    // $data[$key]['jenis_arsip'] = $listData[$key]->jenis_arsip;
                    // $data[$key]['kategori'] = $listData[$key]->category ? $listData[$key]->category->nama_kategori : ' ';
                    // $data[$key]['kataSama'] = 0;
                    // $data[$key]['cosSim'] = 0;
                    $data[$key]['id_arsip'] = $listData[$key]->id_tbl_uu;
                    $data[$key]['judul_arsip'] = $listData[$key]->uu;
                    $data[$key]['jenis_arsip'] = $listData[$key]->tentang;
                    $data[$key]['kategori'] = $listData[$key]->category ? $listData[$key]->category->nama_kategori : ' ';
                    $data[$key]['kataSama'] = 0;
                    $data[$key]['cosSim'] = 0;
                }
            }

            $sort = array();
            foreach ($data as $key => $row) {
                $sort[$key] = $row['cosSim'];
            }
            array_multisort($sort, SORT_DESC, $data);
            foreach ($data as $key => $value) {
                if ($data[$key]['kataSama'] == 0) {
                    unset($data[$key]);
                }
            }

            // RETURN THE RESULT TO DATATABLE FORMAT
            $theme = $request->theme;
            return DataTables::of($data)
                ->editColumn('cosSim', 'components.data-table.draft-cosSim')
                ->addIndexColumn()
                ->addColumn('detail', function ($row) use ($theme) {
                    return view('components.data-table.draft-detail', compact(['row', 'theme']));
                })
                ->rawColumns(['detail'])
                ->make(true);
        }
    }



    public function show(Request $request, $id)
    {
        // PAGE SETUP
        if (!$request->theme) {
            return redirect(route('draft.index'))->with('failed', 'Error saat membaca kata kunci!');
        }
        $pageTitle = 'Detail';
        $active = 'Drafting';
        $breadCrumbs = [
            'bx-icon' => 'bx bxs-book-content',
            'list' => [
                ['title' => 'Drafting', 'url' => route('archive.index')],
                ['title' => 'Detail', 'url' => ''],
            ]
        ];
        // GET DATA
        $archiveUU = Archive::with(['category'])->find($id);
        $penuh = $this->calcSimilarity($request->theme, $archiveUU);
        $pasals = Pasal::with(['uu'])
            ->where('id_tbl_uu', $id)
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
            $simplePasal[$i]['uud_content'] = $this->findSimilarity($request->theme, $item->uud_content);
            // $item->uud_content .= $this->findSimilarity($request->theme, $item->uud_content);
            $i++;
        }

        return view('pages.draft-detail', [
            'user' => Auth::user(),
            'pageTitle' => $pageTitle,
            'active' => $active,
            'breadCrumbs' => $breadCrumbs,
            'navs' => $this->NavigationList(),
            'theme' => $request->theme,
            'pasals' => $pasals,
            'simplePasal' => $simplePasal,
            'archiveUU' => $archiveUU,
            'penuh' => $penuh,
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

    private function calcSimilarity($theme, $data)
    {
        // GET THE ARCHIVE OF CURRENT ID
        $arsip1 = $data;
        // STEM THE THEM INPUT
        $stemmerFactory = new StemmerFactory;
        $stemmer = $stemmerFactory->createStemmer();
        $stemmingQuery = $stemmer->stem($theme);
        // TRANSFORM QUERY INTO ARRAY
        $query = explode(' ', $stemmingQuery);

        // GET ARCHIVE UU FILE PATH
        $pdfPath = public_path('assets\pdf\\' . $data->file_arsip);
        if (file_exists($pdfPath)) {
            $pdfParser = new Parser();
            $pdf = $pdfParser->parseFile($pdfPath);
            $newArsipPembanding1 = $pdf->getText();

            $newArsipPembanding1 = str_ireplace("\n", "<br>", $newArsipPembanding1);

            $stemminghtml = explode("<br>", $newArsipPembanding1);

            foreach ($stemminghtml as $key => $value) {
                $stemminghtml[$key] = explode(" ", $value);
            }

            foreach ($stemminghtml as $key => $value) {
                foreach ($value as $key1 => $value1) {
                    if (
                        in_array($stemmer->stem(strtolower($stemminghtml[$key][$key1])), $query)
                    ) {
                        $stemminghtml[$key][$key1] = '<span style="background: yellow">' . $stemminghtml[$key][$key1] . '</span>';
                    }
                }
            }

            foreach ($stemminghtml as $key => $value) {
                $stemminghtmlnew[$key] = implode(' ', $value);
            }
            $stemminghtmlnew = implode("<br>", $stemminghtmlnew);

            return $stemminghtmlnew;
        } else {
            return 'File tidak ditemukan';
        }
    }

    public function exportDraft(Request $request)
    {
        // GET DATA FOR EXPORT
        $pasals = explode(',', $request->pasals);
        $pasalResults = Pasal::with(['uu'])->findMany($pasals);

        $pdf = MPDF::loadView('pages.drafting.export', [
            'data' => $pasalResults,
        ], [], [
            'margin_top' => 25
        ]);

        return $pdf->stream('OMNILAW_DRAFT_' . date('ymdhi'));
    }

    public function exportDraftToWord(Request $request)
    {
        $pasals = explode(',', $request->pasals);
        $pasalResults = Pasal::with(['uu'])->findMany($pasals);
        $phpWord = new \PhpOffice\PhpWord\PhpWord();

        // Adding Text element with font customized using explicitly created font style object...
        $fontBold = new \PhpOffice\PhpWord\Style\Font();
        $fontBold->setBold(true);

        $filename = 'DRAFTING RESULT.docx';
        $section = $phpWord->addSection();
        $section->addText('Hasil Drafting Undang-Undang')->setFontStyle($fontBold);
        foreach ($pasalResults as $item) {
            $section->addText('');
            $section->addText($item->uu->uu .' '. $item->uu->tentang)->setFontStyle($fontBold);
            $section->addText($item->uud_id);
            $section->addText(strip_tags($item->uud_content));
        }


        // Saving the document as OOXML file...
        $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $objWriter->save($filename);

        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=' . $filename);
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filename));
        flush();
        readfile($filename);
        unlink($filename); // deletes the temporary file
        exit;
    }
}
