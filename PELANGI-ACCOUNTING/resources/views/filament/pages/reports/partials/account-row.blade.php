@props(['account', 'level' => 0])

@if(!$account->is_header && $account->calculated_balance == 0)
{{-- Skip rendering leaf nodes with zero balance --}}
@elseif($account->is_header)
{{-- Header Line (No Amount) --}}
<tr style="background-color: #f9fafb;">
    <td style="padding-left: {{ 0.8 + ($level * 1.5) }}rem; font-weight: bold; color: #1e3a8a; text-transform: uppercase;">
        {{ $account->name }}
    </td>
    <td class="num">
    </td>
</tr>

{{-- Children --}}
@foreach($account->children as $child)
@include('filament.pages.reports.partials.account-row', ['account' => $child, 'level' => $level + 1])
@endforeach

{{-- Header Total Line --}}
<tr>
    <td style="padding-left: {{ 0.8 + ($level * 1.5) }}rem; font-weight: 600; color: #1f2937;">Total {{ $account->name
        }}</td>
    <td class="num" style="font-weight: 600; color: #1f2937; border-top: 1px solid #d1d5db;">{{
        number_format($account->calculated_balance, 2, ',', '.') }}</td>
</tr>
@else
{{-- Leaf Node --}}
<tr>
    <td style="padding-left: {{ 0.8 + ($level * 1.5) }}rem; color: #1f2937;">
        {{ $account->name }}
        <span style="font-size: 0.75rem; color: #9ca3af; margin-left: 0.5rem; font-family: monospace;">{{ $account->code
            }}</span>
    </td>
    <td class="num">
        {{ number_format($account->calculated_balance, 2, ',', '.') }}
    </td>
</tr>
@endif