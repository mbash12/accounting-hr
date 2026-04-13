@php
$isVisible = ($account->is_header && $account->children->isNotEmpty()) || (!$account->is_header &&
$account->calculated_balance != 0);
@endphp

@if($isVisible)
@if($account->is_header)
<tr>
    <td style="padding-left: {{ $level * 15 + 5 }}px; font-weight: bold;">
        {{ strtoupper($account->name) }}
    </td>
    <td class="amount font-bold">
        @if($account->calculated_balance < 0) - {{ number_format(abs($account->calculated_balance), 0, ',', ',') }}
            @else {{ number_format($account->calculated_balance, 0, ',', ',') }} @endif
    </td>
</tr>
@else
<tr>
    <td style="padding-left: {{ $level * 15 + 5 }}px;">
        {{ $account->name }}
    </td>
    <td class="amount">
        @if($account->calculated_balance < 0) - {{ number_format(abs($account->calculated_balance), 0, ',', ',') }}
            @else {{ number_format($account->calculated_balance, 0, ',', ',') }} @endif
    </td>
</tr>
@endif

@foreach($account->children as $child)
@include('filament.pages.reports.partials.cash-flow-row-pdf', ['account' => $child, 'level' => $account->is_header ?
$level + 1 : $level])
@endforeach
@endif