@php
$visibleChildren = $account->children;
// Note: PDF generation usually renders all without search filtering,
// but applying it just in case logic falls through with filteredAccountIds
@endphp

<tr>
    <td style="padding-top: 5px; padding-bottom: 5px; font-family: monospace; border-bottom: 1px solid #e5e7eb;">
        {{ $account->code }}
    </td>
    <td
        style="padding-top: 5px; padding-bottom: 5px; font-weight: {{ $account->is_header ? 'bold' : 'normal' }}; border-bottom: 1px solid #e5e7eb; padding-left: {{ $level * 15 }}px;">
        {{ $account->name }}
    </td>
    <td style="padding-top: 5px; padding-bottom: 5px; color: #666; border-bottom: 1px solid #e5e7eb;">
        {{ ucwords(str_replace('_', ' ', $account->account_type)) }}
    </td>
    <td class="amount"
        style="padding-top: 5px; padding-bottom: 5px; font-weight: {{ $account->is_header ? 'bold' : 'normal' }}; color: {{ $account->calculated_balance < 0 ? '#dc2626' : '#333' }}; border-bottom: 1px solid #e5e7eb;">
        {{ number_format(abs($account->calculated_balance), 2, ',', '.') }}{{ $account->calculated_balance < 0 ? ' (Cr)'
            : '' }} </td>
</tr>

@foreach($visibleChildren as $child)
@include('filament.pages.reports.partials.account-balances-tree-pdf', ['account' => $child, 'level' => $level + 1])
@endforeach