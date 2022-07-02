<?php

namespace App\Http\Controllers;

use App\Models\Archive;
use App\Models\PreprocessingPasal;
use App\Models\Stemming;
use Illuminate\Support\Facades\Http;
use Sastrawi\Stemmer\StemmerFactory;

trait PrepareArchive
{
    public function processPDF($content)
    {
        // make all char Lowercase
        // $contentLowerCase = strtolower($content);
        // remove tab
        $content = str_replace("\t", '', $content);
        // explode by \n
        $arrContent = explode("\n", $content);

        // REQURED INFORMATION
        $totalPasal = 62;
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

        return $arrContent;
    }

    public function simpan($archive)
    {
        // APPLY PREPROCESSING FROM FLASK APPLICATION
        // DONT FORGET TO CHANGE FILE PATH IN FLASK APP
        $url = 'http://localhost:8080/v1/preprocessing_uu';
        $prep = array(
            'id_tbl_uu' => $archive->id_tbl_uu,
            'file' => $archive->file_arsip
        );
        $response = Http::withBody(json_encode($prep), "application/json")->post($url);
        $ekstrak = $response->json();

        $textResponse = $ekstrak['value'][0]['text'];
        // UPDATE TEXT FIELD IN ARCHIVE TABLE
        Archive::find($archive->id_tbl_uu)->update([
            'text' => $textResponse
        ]);

        // GET UPDATED ARCHIVE DATA
        $dataset = Archive::find($archive->id_tbl_uu);

        // PREPARE TEXT  FOR PROCESSING
        $text = strtolower($dataset->text);
        $text = str_replace(
            array(
                '0', '1', '2', '3', '4', '5', '6', '7', '8', '9',
                '(', ')', ',', '.', '=', ';', '!', '?', '"', '$',
                '/', '\\', '%', '&', '#', '@', '^', '*', ':', '+', ';'
            ),
            '',
            $text
        );
        $text = str_replace('-', ' ', $text);

        $caseFolding[] = array(
            "text" => $text,
        );

        // ATTEMPTING TOKENIZING PROCESS
        foreach ($caseFolding as $fold) {
            $array = explode(" ", $fold['text']);
            foreach ($array as $key => $value) {
                if ($value == "") {
                    unset($array[$key]);
                }
            }
            $coba = array_map('utf8_encode', $array);
            $inputText = str_replace(
                array('\r', '\n', '\f', 'u201d', 'u201c', 'u2026'),
                '',
                json_encode(array_values($coba))
            );
            $inputText = str_replace(array(',""', '\\'), '', $inputText);

            $tokenizing[] = $inputText;
        }

        // GET STOPWORD
        $stopword = $this->array_stopword();

        $filtering = array();
        foreach ($tokenizing as $token) {
            $newArray = array();
            $oldArray[] = json_decode($token);
        }

        foreach ($oldArray as $old_key => $word) {
            foreach ($word as $key_w => $value_w) {
                if (!in_array($value_w, $stopword)) {
                    $filtering[$old_key][] = $value_w;
                }
            }
        }
        $stemmerFactory = new StemmerFactory;
        $stemmer  = $stemmerFactory->createStemmer();
        foreach ($tokenizing as $key => $filter) {
            $kata = json_decode($filter);
            $sentence = implode(" ", $kata);
            $stemming   = $stemmer->stem($sentence);
            $newArray = explode(" ", $stemming);
            $insertStemming = array(
                "array" => json_encode(array_values($newArray)),
                "id_tbl_uu" => $dataset->id_tbl_uu
            );
            $insertStemming = Stemming::create($insertStemming);
        }
    }

    // public function simpanPasal($insert)
    // {
    //     $id_uu_pasal = $insert->id;
    //     $prep = array(
    //         'id_uu_pasal' => $id_uu_pasal,
    //         'uud_content' => $insert->uud_content,
    //     );
    //     $url = 'http://localhost:5000/undang/tambahPasal';

    //     $response = Http::withBody(json_encode($prep), "application/json")->post($url);
    //     $result = $response->json();

    //     $insertPre = PreprocessingPasal::create([
    //         'id_uu_pasal' => $id_uu_pasal,
    //         'uud_detail' => $result['values'],
    //     ]);
    // }
    public function simpanPasalBulk($pasalToInsert)
    {

        $url = 'http://localhost:8080/v1/preprocessing_bulk';

        $response = Http::withBody(json_encode([
            'data' => $pasalToInsert
        ]), "application/json")
            ->post($url);
        $result = $response->json();
    }

    function array_stopword()
    {
        $stopword = array(
            "ada", "adalah", "adanya", "adapun", "agak", "agaknya", "agar", "akan", "akankah", "akhir", "akhiri", "akhirnya", "aku",
            "akulah", "amat", "amatlah", "anda", "ndalah", "antar", "antara", "antaranya", "apa", "apaan", "apabila", "apakah", "apalagi",
            "apatah", "artinya", "asal", "asalkan", "atas", "atau", "ataukah", "ataupun", "awal", "awalnya", "bagai", "bagaikan",
            "bagaimana", "bagaimanakah", "bagaimanapun", "bagi", "bagian", "bahkan", "bahwa", "bahwasanya", "baik", "bakal", "bakalan",
            "balik", "banyak", "bapak", "baru", "bawah", "beberapa", "begini", "beginian", "beginikah", "beginilah", "begitu", "begitukah",
            "begitulah", "begitupun", "bekerja", "belakang", "belakangan", "belum", "belumlah", "benar", "benarkah", "benarlah", "berada",
            "berakhir", "berakhirlah", "berakhirnya", "berapa", "berapakah", "berapalah", "berapapun", "berarti", "berawal", "berbagai",
            "berdatangan", "beri", "berikan", "berikut", "berikutnya", "berjumlah", "berkali-kali", "berkata", "berkehendak",
            "berkeinginan", "berkenaan", "berlainan", "berlalu", "berlangsung", "berlebihan", "bermacam", "bermacam-macam", "bermaksud",
            "bermula", "bersama", "bersama-sama", "bersiap", "bersiap-siap", "bertanya", "bertanya-tanya", "berturut", "berturut-turut",
            "bertutur", "berujar", "berupa", "besar", "betul", "betulkah", "biasa", "biasanya", "bila", "bilakah", "bisa", "bisakah",
            "boleh", "bolehkah", "bolehlah", "buat", "bukan", "bukankah", "bukanlah", "bukannya", "bulan", "bung", "cara", "caranya", "cukup",
            "cukupkah", "cukuplah", "cuma", "dahulu", "dalam", "dan", "dapat", "dari", "daripada", "datang", "dekat", "demi", "demikian",
            "demikianlah", "dengan", "depan", "di", "dia", "diakhiri", "diakhirinya", "dialah", "diantara", "diantaranya", "diberi",
            "diberikan", "diberikannya", "dibuat", "dibuatnya", "didapat", "didatangkan", "digunakan", "diibaratkan", "diibaratkannya",
            "diingat", "diingatkan", "diinginkan", "dijawab", "dijelaskan", "dijelaskannya", "dikarenakan", "dikatakan", "dikatakannya",
            "dikerjakan", "diketahui", "diketahuinya", "dikira", "dilakukan", "dilalui", "dilihat", "dimaksud", "dimaksudkan",
            "dimaksudkannya", "dimaksudnya", "diminta", "dimintai", "dimisalkan", "dimulai", "dimulailah", "dimulainya", "dimungkinkan",
            "dini", "dipastikan", "diperbuat", "diperbuatnya", "dipergunakan", "diperkirakan", "diperlihatkan", "diperlukan",
            "diperlukannya", "dipersoalkan", "dipertanyakan", "dipunyai", "diri", "dirinya", "disampaikan", "disebut", "disebutkan",
            "disebutkannya", "disini", "disinilah", "ditambahkan", "ditandaskan", "ditanya", "ditanyai", "ditanyakan", "ditegaskan",
            "ditujukan", "ditunjuk", "ditunjuk", "iditunjukkan", "ditunjukkannya", "ditunjuknya", "dituturkan", "dituturkannya",
            "diucapkan", "diucapkannya", "diungkapkan", "dong", "dua", "dulu", "empat", "enggak", "enggaknya", "entah", "entahlah", "guna",
            "gunakan", "hal", "hampir", "hanya", "hanyalah", "hari", "harus", "haruslah", "harusnya", "hendak", "hendaklah", "hendaknya",
            "hingga", "ia", "ialah", "ibarat", "ibaratkan", "ibaratnya", "ibu", "ikut", "ingat", "ingat-ingat", "ingin", "inginkah",
            "inginkan", "ini", "inikah", "inilah", "itu", "itukah", "itulah", "jadi", "jadilah", "jadinya", "jangan", "jangankan", "janganlah",
            "jauh", "jawab", "jawaban", "jawabnya", "jelas", "jelaskan", "jelaslah", "jelasnya", "jika", "jikalau", "juga", "jumlah",
            "jumlahnya", "justru", "kala", "kalau", "kalaulah", "kalaupun", "kalian", "kami", "kamilah", "kamu", "kamulah", "kan", "kapan",
            "kapankah", "kapanpun", "karena", "karenanya", "kasus", "kata", "katakan", "katakanlah", "katanya", "ke", "keadaan", "kebetulan",
            "kecil", "kedua", "keduanya", "keinginan", "kelamaan", "kelihatan", "kelihatannya", "kelima", "keluar", "kembali",
            "kemudian", "kemungkinan", "kemungkinannya", "kenapa", "kepada", "kepadanya", "kesampaian", "keseluruhan", "keseluruhannya",
            "keterlaluan", "ketika", "khususnya", "kini", "kinilah", "kira", "kira-kira", "kiranya", "kita", "kitalah", "kok", "kurang",
            "lagi", "lagian", "lah", "lain", "lainnya", "lalu", "lama", "lamanya", "lanjut", "lanjutnya", "lebih", "lewat", "lima", "luar",
            "macam", "maka", "makanya", "makin", "malah", "malahan", "mampu", "mampukah", "mana", "manakala", "manalagi", "masa", "masalah",
            "masalahnya", "masih", "masihkah", "masing", "masing-masing", "mau", "maupun", "melainkan", "melakukan", "melalui", "melihat",
            "melihatnya", "memang", "memastikan", "memberi", "memberikan", "membuat", "memerlukan", "memihak", "meminta", "memintakan",
            "memisalkan", "memperbuat", "mempergunakan", "memperkirakan", "memperlihatkan", "mempersiapkan", "mempersoalkan",
            "mempertanyakan", "mempunyai", "memulai", "memungkinkan", "menaiki", "menambahkan", "menandaskan", "menanti", "menanti-nanti",
            "menantikan", "menanya", "menanyai", "menanyakan", "mendapat", "mendapatkan", "mendatang", "mendatangi", "mendatangkan",
            "menegaskan", "mengakhiri", "mengapa", "mengatakan", "mengatakannya", "mengenai", "mengerjakan", "mengetahui", "menggunakan",
            "menghendaki", "mengibaratkan", "mengibaratkannya", "mengingat", "mengingatkan", "menginginkan", "mengira", "mengucapkan",
            "mengucapkannya", "mengungkapkan", "menjadi", "menjawab", "menjelaskan", "menuju", "menunjuk", "menunjuki", "menunjukkan",
            "menunjuknya", "menurut", "menuturkan", "menyampaikan", "menyangkut", "menyatakan", "menyebutkan", "menyeluruh",
            "menyiapkan", "merasa", "mereka", "merekalah", "merupakan", "meski", "meskipun", "meyakini", "meyakinkan", "minta",
            "mirip", "misal", "misalkan", "misalnya", "mula", "mulai", "mulailah", "mulanya", "mungkin", "mungkinkah", "nah", "naik",
            "namun", "nanti", "nantinya", "nyaris", "nyatanya", "oleh", "olehnya", "pada", "padahal", "padanya", "pak", "paling",
            "panjang", "pantas", "para", "pasti", "pastilah", "penting", "pentingnya", "per", "percuma", "perlu", "perlukah",
            "perlunya", "pernah", "persoalan", "pertama", "pertama-tama", "pertanyaan", "pertanyakan", "pihak", "pihaknya", "pukul",
            "pula", "pun", "punya", "rasa", "rasanya", "rata", "rupanya", "saat", "saatnya", "saja", "sajalah", "saling", "sama",
            "sama-sama", "sambil", "sampai", "sampai-sampai", "sampaikan", "sana", "sangat", "sangatlah", "satu", "saya", "sayalah", "se",
            "sebab", "sebabnya", "sebagai", "sebagaimana", "sebagainya", "sebagian", "sebaik", "sebaik-baiknya", "sebaiknya",
            "sebaliknya", "sebanyak", "sebegini", "sebegitu", "sebelum", "sebelumnya", "sebenarnya", "seberapa", "sebesar",
            "sebetulnya", "sebisanya", "sebuah", "sebut", "sebutlah", "sebutnya", "secara", "secukupnya", "sedang", "sedangkan",
            "sedemikian", "sedikit", "sedikitnya", "seenaknya", "segala", "segalanya", "segera", "seharusnya", "sehingga",
            "seingat", "sejak", "sejauh", "sejenak", "sejumlah", "sekadar", "sekadarnya", "sekali", "sekali-kali", "sekalian",
            "sekaligus", "sekalipun", "sekarang", "sekarang", "sekecil", "seketika", "sekiranya", "sekitar", "sekitarnya",
            "sekurang-kurangnya", "sekurangnya", "sela", "selain", "selaku", "selalu", "selama", "selama-lamanya", "selamanya",
            "selanjutnya", "seluruh", "seluruhnya", "semacam", "semakin", "semampu", "semampunya", "semasa", "semasih", "semata",
            "semata-mata", "semaunya", "sementara", "semisal", "semisalnya", "sempat", "semua", "semuanya", "semula", "sendiri",
            "sendirian", "sendirinya", "seolah", "seolah-olah", "seorang", "sepanjang", "sepantasnya", "sepantasnyalah", "seperlunya",
            "seperti", "sepertinya", "sepihak", "sering", "seringnya", "serta", "serupa", "sesaat", "sesama", "sesampai", "sesegera",
            "sesekali", "seseorang", "sesuatu", "sesuatunya", "sesudah", "sesudahnya", "setelah", "setempat", "setengah", "seterusnya",
            "setiap", "setiba", "setibanya", "setidak-tidaknya", "setidaknya", "setinggi", "seusai", "sewaktu", "siap", "siapa", "siapakah",
            "siapapun", "sini", "sinilah", "soal", "soalnya", "suatu", "sudah", "sudahkah", "sudahlah", "supaya", "tadi", "tadinya",
            "tahu", "tahun", "tak", "tambah", "tambahnya", "tampak", "tampaknya", "tandas", "tandasnya", "tanpa", "tanya", "tanyakan",
            "tanyanya", "tapi", "tegas", "tegasnya", "telah", "tempat", "tengah", "tentang", "tentu", "tentulah", "tentunya", "tepat",
            "terakhir", "terasa", "terbanyak", "terdahulu", "terdapat", "terdiri", "terhadap", "terhadapnya", "teringat", "teringat-ingat",
            "terjadi", "terjadilah", "terjadinya", "terkira", "terlalu", "terlebih", "terlihat", "termasuk", "ternyata", "tersampaikan",
            "tersebut", "tersebutlah", "tertentu", "tertuju", "terus", "terutama", "tetap", "tetapi", "tiap", "tiba", "tiba-tiba",
            "tidak", "tidakkah", "tidaklah", "tiga", "tinggi", "toh", "tunjuk", "turut", "tutur", "tuturnya", "ucap", "ucapnya", "ujar",
            "ujarnya", "umum", "umumnya", "ungkap", "ungkapnya", "untuk", "usah", "usai", "waduh", "wah", "wahai", "waktu", "waktunya",
            "walau", "walaupun", "wong", "yaitu", "yakin", "yakni", "yang",
            //tambahan
            "indonesiamenimbang", "huruf",  "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z", "nomor", "pasal"
        );

        return $stopword;
    }
}
