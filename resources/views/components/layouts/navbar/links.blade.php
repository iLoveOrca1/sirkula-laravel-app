@props(['vertical' => false])

@php
    $links = [
        ['label' => 'Beranda',         'href' => url('/'),          'active' => request()->is('/')],
        ['label' => 'Jelajahi Limbah', 'href' => url('/limbah'),    'active' => request()->is('limbah*')],
        ['label' => 'Tentang',         'href' => url('/tentang'),   'active' => request()->is('tentang*')],
        ['label' => 'Dashboard',       'href' => url('/dashboard'), 'active' => request()->is('dashboard*')],
    ];
@endphp

<ul {{ $attributes->class([
    'items-center text-sm',
    'flex flex-col items-stretch gap-1' => $vertical,
    'flex gap-8' => ! $vertical,
]) }}>
    @foreach ($links as $link)
        <li>
            <a href="{{ $link['href'] }}"
               @if ($link['active']) aria-current="page" @endif
               @class([
                   'transition-colors hover:text-primary focus-visible:outline-2 focus-visible:outline-offset-4 focus-visible:outline-primary',
                   'block rounded-md px-3 py-2 hover:bg-primary/5' => $vertical,
                   'font-semibold text-primary' => $link['active'],
                   'text-ink' => ! $link['active'],
               ])>
                {{ $link['label'] }}
            </a>
        </li>
    @endforeach
</ul>