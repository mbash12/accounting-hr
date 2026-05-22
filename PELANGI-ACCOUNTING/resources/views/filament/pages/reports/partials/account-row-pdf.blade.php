@props(['account', 'level' => 0])

@if(!$account->is_header && $account->calculated_balance == 0)
{{-- Skip rendering leaf nodes with zero balance --}}
@elseif($account->is_header)
{{-- Header Line (No Amount) --}}
<tr style="background-color: #f9fafb;">
    <td style="padding-left: {{ 5 + ($level * 15) }}px; font-weight: bold; color: #1e3a8a; text-transform: uppercase;">
        {{ $account->name }}
    </td>
    <td class="amount">
    </td>
</tr>

{{-- Children --}}
@foreach($account->children as $child)
@include('filament.pages.reports.partials.account-row-pdf', ['account' => $child, 'level' => $level + 1])
@endforeach

{{-- Header Total Line --}}
<tr>
    <td style="padding-left: {{ 5 + ($level * 15) }}px; font-weight: bold; color: #1f2937;">Total {{ $account->name }}
    </td>
    <td class="amount" style="font-weight: bold; color: #1f2937; border-top: 1px solid #d1d5db;">{{
        number_format($account->calculated_balance, 2, ',', '.') }}</td>
</tr>
@else
{{-- Leaf Node --}}
<tr>
    <td style="padding-left: {{ 5 + ($level * 15) }}px; color: #1f2937;">
        {{ $account->name }}
        <span style="font-size: 10px; color: #9ca3af; margin-left: 5px; font-family: monospace;">{{ $account->code
            }}</span>
    </td>
    <td class="amount">
        {{ number_format($account->calculated_balance, 2, ',', '.') }}
    </td>
</tr>
@endif