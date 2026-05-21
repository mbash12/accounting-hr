<div class="journal-voucher-wrapper" style="background: white; padding: 0;">
    <div style="margin-bottom: 24px; padding-bottom: 16px; border-bottom: 1px solid #e5e7eb; display: flex; justify-content: space-between; align-items: center;">
        <h2 style="font-size: 24px; font-weight: 700; color: #111827; margin: 0;">{{ __('Voucher Jurnal') }}</h2>
        <div style="display: flex; gap: 8px;">
            <a href="{{ route('journal-voucher.print', $journalEntry->id) }}" target="_blank" style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background: #6b7280; color: white; text-decoration: none; border-radius: 6px; font-size: 14px; font-weight: 500;">
                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                </svg>
                {{ __('Cetak (A4)') }}
            </a>
            <a href="{{ route('journal-voucher.pdf', $journalEntry->id) }}" target="_blank" style="display: inline-flex; align-items: center; gap: 6px; padding: 8px 16px; background: #6b7280; color: white; text-decoration: none; border-radius: 6px; font-size: 14px; font-weight: 500;">
                <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                </svg>
                {{ __('Ekspor ke PDF') }}
            </a>
        </div>
    </div>

    <div style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 20px; margin-bottom: 24px;">
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 20px;">
            <div>
                <div style="font-size: 12px; font-weight: 500; color: #6b7280; margin-bottom: 4px;">{{ __('Nomor Entri') }}</div>
                <div style="font-size: 14px; font-weight: 600; color: #111827;">{{ $journalEntry->entry_number }}</div>
            </div>
            <div>
                <div style="font-size: 12px; font-weight: 500; color: #6b7280; margin-bottom: 4px;">{{ __('Tanggal') }}</div>
                <div style="font-size: 14px; font-weight: 600; color: #111827;">{{ $journalEntry->date->format('d/m/Y') }}</div>
            </div>
            <div>
                <div style="font-size: 12px; font-weight: 500; color: #6b7280; margin-bottom: 4px;">{{ __('Nomor Referensi') }}</div>
                <div style="font-size: 14px; font-weight: 600; color: #111827;">{{ $journalEntry->reference_no ?? '-' }}</div>
            </div>
            <div>
                <div style="font-size: 12px; font-weight: 500; color: #6b7280; margin-bottom: 4px;">{{ __('Status') }}</div>
                <span style="display: inline-block; padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: 500; 
                    {{ $journalEntry->status === 'posted' ? 'background: #dcfce7; color: #166534;' : 'background: #fef3c7; color: #92400e;' }}">
                    {{ ucfirst($journalEntry->status) }}
                </span>
            </div>
            @if($journalEntry->description)
            <div style="grid-column: span 2;">
                <div style="font-size: 12px; font-weight: 500; color: #6b7280; margin-bottom: 4px;">{{ __('Deskripsi') }}</div>
                <div style="font-size: 14px; color: #111827;">{{ $journalEntry->description }}</div>
            </div>
            @endif
            {{-- @if($journalEntry->department)
            <div>
                <div style="font-size: 12px; font-weight: 500; color: #6b7280; margin-bottom: 4px;">{{ __('Department') }}</div>
                <div style="font-size: 14px; color: #111827;">{{ $journalEntry->department->name }}</div>
            </div>
            @endif --}}
            @if($journalEntry->company)
            <div>
                <div style="font-size: 12px; font-weight: 500; color: #6b7280; margin-bottom: 4px;">{{ __('Perusahaan') }}</div>
                <div style="font-size: 14px; color: #111827;">{{ $journalEntry->company->name }}</div>
            </div>
            @endif
            @if($journalEntry->postedByUser)
            <div>
                <div style="font-size: 12px; font-weight: 500; color: #6b7280; margin-bottom: 4px;">{{ __('Diposting Oleh') }}</div>
                <div style="font-size: 14px; color: #111827;">{{ $journalEntry->postedByUser->name }}</div>
            </div>
            @endif
        </div>
    </div>

    <div style="background: white; border: 1px solid #e5e7eb; border-radius: 8px; overflow: hidden; margin-bottom: 24px;">
        <table style="width: 100%; border-collapse: collapse; table-layout: fixed;">
            <colgroup>
                <col style="width: 12%;">
                <col style="width: 25%;">
                <col style="width: 18%;">
                <col style="width: 18%;">
                <col style="width: 27%;">
            </colgroup>
            <thead>
                <tr style="background: #eff6ff; border-bottom: 2px solid #bfdbfe;">
                    <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 600; color: #1e40af; border-right: 1px solid #bfdbfe;">{{ __('Kode Akun') }}</th>
                    <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 600; color: #1e40af; border-right: 1px solid #bfdbfe;">{{ __('Nama Akun') }}</th>
                    {{-- <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 600; color: #1e40af; border-right: 1px solid #bfdbfe;">{{ __('Pusat Biaya') }}</th> --}}
                    <th style="padding: 12px 16px; text-align: right; font-size: 12px; font-weight: 600; color: #1e40af; border-right: 1px solid #bfdbfe;">{{ __('Debit') }}</th>
                    <th style="padding: 12px 16px; text-align: right; font-size: 12px; font-weight: 600; color: #1e40af; border-right: 1px solid #bfdbfe;">{{ __('Kredit') }}</th>
                    <th style="padding: 12px 16px; text-align: left; font-size: 12px; font-weight: 600; color: #1e40af;">{{ __('Catatan') }}</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalDebit = 0;
                    $totalCredit = 0;
                @endphp
                @foreach($journalEntry->items as $item)
                    @php
                        $totalDebit += $item->debit;
                        $totalCredit += $item->credit;
                    @endphp
                    <tr style="border-bottom: 1px solid #e5e7eb;">
                        <td style="padding: 12px 16px; font-size: 13px; font-weight: 500; color: #111827; border-right: 1px solid #e5e7eb;">{{ $item->account->code ?? $item->account->account_code ?? '-' }}</td>
                        <td style="padding: 12px 16px; font-size: 13px; color: #111827; border-right: 1px solid #e5e7eb;">{{ $item->account->name ?? '-' }}</td>
                        {{-- <td style="padding: 12px 16px; font-size: 13px; color: #6b7280; border-right: 1px solid #e5e7eb;">{{ $item->costCenter->name ?? 'Default' }}</td> --}}
                        <td style="padding: 12px 16px; font-size: 13px; text-align: right; font-weight: 500; color: #111827; border-right: 1px solid #e5e7eb;">
                            @if($item->debit > 0)
                                Rp {{ number_format($item->debit, 0, ',', '.') }}
                            @else
                                <span style="color: #9ca3af;">-</span>
                            @endif
                        </td>
                        <td style="padding: 12px 16px; font-size: 13px; text-align: right; font-weight: 500; color: #111827; border-right: 1px solid #e5e7eb;">
                            @if($item->credit > 0)
                                Rp {{ number_format($item->credit, 0, ',', '.') }}
                            @else
                                <span style="color: #9ca3af;">-</span>
                            @endif
                        </td>
                        <td style="padding: 12px 16px; font-size: 13px; color: #6b7280;">{{ $item->notes ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr style="background: #f3f4f6; border-top: 2px solid #9ca3af;">
                    <td colspan="2" style="padding: 12px 16px; text-align: right; font-size: 13px; font-weight: 700; color: #111827; border-right: 1px solid #9ca3af;">{{ __('Total') }}:</td>
                    <td style="padding: 12px 16px; text-align: right; font-size: 13px; font-weight: 700; color: #111827; border-right: 1px solid #9ca3af;">Rp {{ number_format($totalDebit, 0, ',', '.') }}</td>
                    <td style="padding: 12px 16px; text-align: right; font-size: 13px; font-weight: 700; color: #111827; border-right: 1px solid #9ca3af;">Rp {{ number_format($totalCredit, 0, ',', '.') }}</td>
                    <td style="padding: 12px 16px;"></td>
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- Balance Status --}}
    @if(abs($totalDebit - $totalCredit) > 0.01)
    <div style="background: #fef2f2; border: 1px solid #fecaca; border-radius: 8px; padding: 12px; margin-bottom: 16px;">
        <div style="display: flex; align-items: center; gap: 8px;">
            <span style="color: #dc2626; font-size: 16px;">⚠️</span>
            <span style="font-size: 13px; font-weight: 500; color: #991b1b;">{{ __('Peringatan: Total Debit dan Kredit tidak seimbang!') }}</span>
        </div>
        <div style="font-size: 12px; color: #b91c1c; margin-top: 4px; margin-left: 24px;">
            {{ __('Selisih: Rp') }} {{ number_format(abs($totalDebit - $totalCredit), 0, ',', '.') }}
        </div>
    </div>
    @else
    <div style="background: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 8px; padding: 12px; margin-bottom: 16px;">
        <div style="display: flex; align-items: center; gap: 8px;">
            <span style="color: #16a34a; font-size: 16px;">✓</span>
            <span style="font-size: 13px; font-weight: 500; color: #166534;">{{ __('Jurnal seimbang: Total Debit = Total Kredit') }}</span>
        </div>
    </div>
    @endif

    @if($journalEntry->posted_at)
    <div style="background: #f9fafb; border: 1px solid #e5e7eb; border-radius: 8px; padding: 16px;">
        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 16px;">
            <div>
                <div style="font-size: 11px; font-weight: 500; color: #6b7280; margin-bottom: 4px;">{{ __('Diposting Pada') }}</div>
                <div style="font-size: 12px; color: #111827;">{{ $journalEntry->posted_at->format('d/m/Y H:i:s') }}</div>
            </div>
            @if($journalEntry->createdByUser)
            <div>
                <div style="font-size: 11px; font-weight: 500; color: #6b7280; margin-bottom: 4px;">{{ __('Dibuat Oleh') }}</div>
                <div style="font-size: 12px; color: #111827;">{{ $journalEntry->createdByUser->name }}</div>
            </div>
            @endif
        </div>
    </div>
    @endif
</div>
