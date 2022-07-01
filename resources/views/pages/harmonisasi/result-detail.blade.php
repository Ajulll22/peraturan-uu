@extends('layouts.app-layout')

@section('content')
<div class="p-5 lg:p-14 lg:py-7">
    <div class="flex justify-between">
        <div class="text-xl font-bold mb-5">
            Detail Harmonisasi Undang-Undang
        </div>
        <div class="hidden lg:block">
            <div id='ck-button' class='ck-button rounded-full border px-3 bg-cyan-500 hover:bg-cyan-600 hover:text-white'>
                <label>
                    <input class='checkboxes ' type='checkbox' value='single' onchange='toggleView(this)'><span>View</span>
                </label>
            </div>
        </div>
    </div>
    <div id="view-double" class="hidden lg:grid grid-cols-1 md:grid-cols-2 divide-x-2 min-h-screen">
        <div class="p-5 shadow-lg rounded bg-white overflow-y-hidden">
            <div class="mb-5 font-bold">Pembanding</div>
            <embed src="{{ $pembandingPath }}" class="w-full h-full">
        </div>
        <div class="p-5 shadow-lg rounded bg-white overflow-y-hidden">
            <div class="mb-5 font-bold">{{ $archive->uu }}</div>
            <embed src="{{ $archivePath }}" class="w-full h-full">
        </div>
    </div>

    <div id="view-single" class="h-screen bg-white rounded-lg shadow-lg blocl lg:hidden">
        <div class="p-5 flex justify-between">
            <div id="single-title" class="font-bold"></div>
            <div id='ck-button' class='ck-button rounded-full border px-3 bg-cyan-500 hover:bg-cyan-600 hover:text-white'>
                <label>
                    <input class='checkboxes ' type='checkbox' value='single' onchange='toggleSingleView(this)'><span><i class='bx bx-shuffle'></i></span>
                </label>
            </div>
        </div>
        <div class="p-5 overflow-y-hidden h-full">
            <embed id="single-1" src="{{ $pembandingPath }}" class="w-full h-full">
            <embed id="single-2" src="{{ $archivePath }}" class="w-full h-full hidden">
        </div>
    </div>
</div>
@endsection

@section('datatable')
<script>
    function toggleView(el) {
        if ($(el).prop("checked")) {
            $(el).attr("checked", false);
            $('#view-double').hide()
            $('#view-single').show()
            $(el).siblings("span").html("Single");
            $(el).parents().eq(1).toggleClass("bg-cyan-600");
        } else {
            $(el).attr("checked", true);
            $('#view-double').show()
            $('#view-single').hide()
            $(el).siblings("span").html("Double");
            $(el).parents().eq(1).toggleClass("bg-cyan-600");
        }
    }

    function toggleSingleView(el) {
        if ($(el).prop("checked")) {
            $(el).attr("checked", false);
            $('#single-1').hide()
            $('#single-2').show()
            $(el).parents().eq(1).toggleClass("bg-cyan-600");
        } else {
            $(el).attr("checked", true);
            $('#single-1').show()
            $('#single-2').hide()
            $(el).parents().eq(1).toggleClass("bg-cyan-600");
        }
    }

    function highlight(text) {
        inputText = document.getElementById("inputText")
        var innerHTML = inputText.innerHTML
        var index = innerHTML.indexOf(text);
        if (index >= 0) {
            innerHTML = innerHTML.substring(0, index) + "<span class='highlight'>" + innerHTML.substring(index, index + text.length) + "</span>" + innerHTML.substring(index + text.length);
            inputText.innerHTML = innerHTML
        }

    }
</script>
@endsection