<div class="mb-3 lg:flex justify-between items-center">
    <div class="text-lg font-semibold">
        {{ $pageTitle }}
    </div>
    <div class="flex flex-row items-center gap-2">
        <i class='{{ $breadCrumbs['bx-icon'] }}'></i>
        @foreach ($breadCrumbs['list'] as $key => $item)
            <li class="text-sm list-none text-slate-700 last:text-slate-500">
                <a href="{{ $item['url'] }}">
                    {{ $item['title'] }}
                </a>
            </li>
            {{-- add > if not last --}}
            @if ($key != count($breadCrumbs['list']) - 1)
                <li class="text-sm list-none text-slate-700">></li>
            @endif
        @endforeach
    </div>
</div>
