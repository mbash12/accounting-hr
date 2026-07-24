@php
$isVisible = ($account->is_header && $account->children->isNotEmpty()) || (!$account->is_header &&
$account->calculated_balance != 0);

$drillStart = $drillStartDate ?? ($reportData['start_date'] ?? null);
$drillEnd = $drillEndDate ?? ($reportData['end_date'] ?? null);
$canDrill = ! $account->is_header
    && filled($account->id)
    && $account->calculated_balance != 0
    && $drillStart
    && $drillEnd;
$drillUrl = $canDrill
    ? \App\Support\ReportDrilldown::generalLedgerUrl($account->id, $drillStart, $drillEnd)
    : null;
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
        @if($drillUrl)
        <a href="{{ $drillUrl }}" class="report-drill-link" title="View General Ledger">{{ $account->name }}</a>
        @else
        {{ $account->name }}
        @endif
    </td>
    <td class="num" style="color: {{ $account->calculated_balance < 0 ? '#dc2626' : '#1f2937' }};">
        @if($drillUrl)<a href="{{ $drillUrl }}" class="report-drill-link" title="View General Ledger">@endif
        @if($account->calculated_balance < 0) - {{ number_format(abs($account->calculated_balance), 0, ',', '.') }}
            @else {{ number_format($account->calculated_balance, 0, ',', '.') }} @endif
        @if($drillUrl)</a>@endif
    </td>
</tr>
@endif

@foreach($account->children as $child)
@include('filament.pages.reports.partials.cash-flow-row', ['account' => $child, 'level' => $account->is_header ? $level
+ 1 : $level])
@endforeach
@endif
