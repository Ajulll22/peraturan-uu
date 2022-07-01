@if ($row->status == 1)
    <span class="p-1 px-2 text-sm font-semibold rounded-md bg-blue-500 text-white">
        Belum Verifikasi
    </span>
@elseif($row->status == 2)
    <span class="p-1 px-2 text-sm font-semibold rounded-md bg-red-500 text-white">
        Tidak berlaku
    </span>
@elseif($row->status == 3)
    <span class="p-1 px-2 text-sm font-semibold rounded-md bg-green-700 text-white">
        Berlaku
    </span>
@endif
