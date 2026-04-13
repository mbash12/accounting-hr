@php
$isVisible = ($account->is_header && $account->children->isNotEmpty()) || (!$account->is_header &&
$account->calculated_balance != 0);
@endphp

@if($isVisible)
@if($account->is_header)
<tr style="background-color: #f9fafb;">
    <td style="padding-left: {{ 0.8 + ($level * 1.5) }}rem; font-weight: bold; color: #1e3a8a;">
        {{ strtoupper($account->name) }}
    </td>
    <td class="num" style="font-weight: bold; color: #1e3a8a;">
        @if($account->calculated_balance < 0) - {{ number_format(abs($account->calculated_balance), 0, ',', '.') }}
            @else {{ number_format($account->calculated_balance, 0, ',', '.') }} @endif
    </td>
</tr>
@else
<tr>
    <td style="padding-left: {{ 0.8 + ($level * 1.5) }}rem; color: #1f2937;">
        {{ $account->name }}
    </td>
    <td class="num" style="color: {{ $account->calculated_balance < 0 ? '#dc2626' : '#1f2937' }};">
        @if($account->calculated_balance < 0) - {{ number_format(abs($account->calculated_balance), 0, ',', '.') }}
            @else {{ number_format($account->calculated_balance, 0, ',', '.') }} @endif
    </td>
</tr>
@endif

@foreach($account->children as $child)
@include('filament.pages.reports.partials.cash-flow-row', ['account' => $child, 'level' => $account->is_header ? $level
+ 1 : $level])
@endforeach
@endif