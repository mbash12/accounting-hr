@php
$visibleChildren = $account->children;
if (!empty($this->search) && !empty($this->filteredAccountIds)) {
$visibleChildren = $visibleChildren->filter(fn($child) => in_array($child->id, $this->filteredAccountIds));
}
@endphp

<tr style="{{ $account->is_header ? 'background-color: #f9fafb;' : '' }}">
    <td style="font-family: monospace; color: #4b5563;">
        <span style="background: #e5e7eb; padding: 0.25rem 0.5rem; border-radius: 0.25rem;">
            {{ $account->code }}
        </span>
    </td>
    <td class="acc-name"
        style="font-weight: {{ $account->is_header ? 'bold' : 'normal' }}; {{ $account->is_header ? 'text-transform: uppercase; color: #1e3a8a;' : '' }} padding-left: {{ 0.8 + ($level * 1.5) }}rem;">
        {{ $account->name }}
    </td>
    <td style="color: #6b7280;">
        {{ ucwords(str_replace('_', ' ', $account->account_type)) }}
    </td>
    <td class="num"
        style="font-weight: {{ $account->is_header ? 'bold' : 'normal' }}; color: {{ $account->calculated_balance < 0 ? '#dc2626' : ( $account->is_header ? '#1f2937' : 'inherit' ) }};">
        {{ number_format(abs($account->calculated_balance), 2, ',', '.') }}{{ $account->calculated_balance < 0 ? ' (Cr)'
            : '' }} </td>
</tr>

@foreach($visibleChildren as $child)
@include('filament.pages.reports.partials.account-balances-tree-item', ['account' => $child, 'level' => $level + 1])
@endforeach