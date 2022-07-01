<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    {{-- FONT CDN --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lato&family=Poppins&family=Rubik:wght@700&display=swap" rel="stylesheet">


    <style>
        .bold {
            font-weight: bold;
        }

    </style>

</head>

<body>
    <div class="text-center bold text-lg">Hasil Drafting Undang-Undang</div>

    <div class="py-5">
        @foreach ($data as $item)
            <div class="mt-5">
                <div class="bold">
                    {{ $item->uu->uu }} - {{ $item->uu->tentang }}
                </div>
                <div class="text-sm capitalize">
                    {{ $item->uud_id }}
                </div>
                <div>
                    {{ $item->uud_content }}
                </div>
            </div>
        @endforeach
    </div>
    <htmlpageheader name="MyHeader1">
        <div class="pt-5 text-left">
            <img src="https://upload.wikimedia.org/wikipedia/commons/f/fe/Garuda_Pancasila%2C_Coat_of_Arms_of_Indonesia.svg" alt="" class="h-10">
            <span class="font-bold text-2xl" style="font-family: 'DejaVuSans'; color: blue">OMNILAW</span>
        </div>
    </htmlpageheader>
    <sethtmlpageheader name="MyHeader1" value="on" show-this-page="1" />
    <htmlpagefooter name="MyFooter1">
        <div class="py-5 text-right">
            Peraturan UU
        </div>
    </htmlpagefooter>

    <sethtmlpagefooter name="MyFooter1" value="on" />

</body>

</html>
