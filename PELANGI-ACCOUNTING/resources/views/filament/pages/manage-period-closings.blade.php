<x-filament-panels::page>
    @php
        $closing = $this->getYearClosing();
        $isClosed = $closing?->isClosed() ?? false;
    @endphp

    <div style="display: flex; flex-direction: column; gap: 1rem;">
        @if(!$this->hasCompanySelected())
            <div style="background: #fffbeb; border: 1px solid #fde68a; border-radius: 0.75rem; padding: 1rem; color: #92400e; font-size: 0.875rem;">
                {{ __('Please select a specific company from the global selector.') }}
            </div>
        @else
            @if(!$this->hasRetainedEarningsMapping())
                <div style="background: #fff1f2; border: 1px solid #fecdd3; border-radius: 0.75rem; padding: 1rem; color: #9f1239; font-size: 0.875rem;">
                    {{ __('Map Retained Earnings under Account Mapping → Period Closing before closing the year.') }}
                </div>
            @endif

            @if($this->canCloseSelectedYear() && ($unposted = $this->getUnpostedCount()) > 0)
                <div style="background: #fff1f2; border: 1px solid #fecdd3; border-radius: 0.75rem; padding: 1rem; color: #9f1239; font-size: 0.875rem;">
                    {{ __('Cannot close year yet: :count unposted journal(s) in :year. Post them in Posting Center first.', ['count' => $unposted, 'year' => $selectedYear]) }}
                </div>
            @endif

            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 0.75rem; padding: 1.5rem; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);">
                <div style="display: flex; flex-wrap: wrap; align-items: flex-start; justify-content: space-between; gap: 1rem;">
                    <div>
                        <div style="font-size: 0.875rem; font-weight: 500; color: #6b7280;">{{ __('Fiscal Year') }}</div>
                        <div style="margin-top: 0.25rem; font-size: 1.875rem; font-weight: 700; color: #111827; line-height: 1.2;">
                            {{ $selectedYear }}
                        </div>
                        <div style="margin-top: 0.25rem; font-size: 0.875rem; color: #6b7280;">
                            01 Jan {{ $selectedYear }} — 31 Dec {{ $selectedYear }}
                        </div>
                    </div>
                    <div>
                        @if($isClosed)
                            <span style="display: inline-flex; align-items: center; border-radius: 9999px; background: #ffe4e6; color: #be123c; padding: 0.25rem 0.75rem; font-size: 0.875rem; font-weight: 600;">
                                {{ __('Closed') }}
                            </span>
                        @else
                            <span style="display: inline-flex; align-items: center; border-radius: 9999px; background: #d1fae5; color: #047857; padding: 0.25rem 0.75rem; font-size: 0.875rem; font-weight: 600;">
                                {{ __('Open') }}
                            </span>
                        @endif
                    </div>
                </div>

                @if($closing)
                    <div style="margin-top: 1.5rem; display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; font-size: 0.875rem; color: #4b5563;">
                        @if($closing->closed_at)
                            <div>
                                <div style="font-weight: 500; color: #6b7280; margin-bottom: 0.15rem;">{{ __('Closed at') }}</div>
                                <div style="color: #111827;">
                                    {{ $closing->closed_at?->format('d M Y H:i') }} — {{ $closing->closedByUser?->name ?? '-' }}
                                </div>
                            </div>
                        @endif
                        @if($closing->closingJournalEntry)
                            <div>
                                <div style="font-weight: 500; color: #6b7280; margin-bottom: 0.15rem;">{{ __('Closing Journal') }}</div>
                                <div style="color: #111827;">{{ $closing->closingJournalEntry->entry_number }}</div>
                                <div style="margin-top: 0.25rem; font-size: 0.75rem; color: #6b7280;">
                                    {{ __('Use “View Closing Journal” above to open the voucher.') }}
                                </div>
                            </div>
                        @elseif($isClosed)
                            <div>
                                <div style="font-weight: 500; color: #6b7280; margin-bottom: 0.15rem;">{{ __('Closing Journal') }}</div>
                                <div style="color: #111827;">{{ __('None (no P&L balances)') }}</div>
                            </div>
                        @endif
                        @if($closing->reopened_at)
                            <div>
                                <div style="font-weight: 500; color: #6b7280; margin-bottom: 0.15rem;">{{ __('Last reopened') }}</div>
                                <div style="color: #111827;">
                                    {{ $closing->reopened_at?->format('d M Y H:i') }} — {{ $closing->reopenedByUser?->name ?? '-' }}
                                </div>
                            </div>
                            <div>
                                <div style="font-weight: 500; color: #6b7280; margin-bottom: 0.15rem;">{{ __('Reason') }}</div>
                                <div style="color: #111827;">{{ $closing->reopen_reason }}</div>
                            </div>
                        @endif
                    </div>
                @endif
            </div>

            <div style="background: white; border: 1px solid #e5e7eb; border-radius: 0.75rem; box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05); overflow: hidden;">
                <div style="padding: 0.75rem 1rem; border-bottom: 1px solid #e5e7eb; background: #f9fafb; font-size: 0.875rem; font-weight: 600; color: #374151;">
                    {{ __('History') }}
                </div>
                <div style="overflow-x: auto;">
                    <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
                        <thead>
                            <tr style="background: #f9fafb; text-align: left; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.04em; color: #6b7280;">
                                <th style="padding: 0.75rem 1rem; font-weight: 600;">{{ __('Year') }}</th>
                                <th style="padding: 0.75rem 1rem; font-weight: 600;">{{ __('Status') }}</th>
                                <th style="padding: 0.75rem 1rem; font-weight: 600;">{{ __('Closed') }}</th>
                                <th style="padding: 0.75rem 1rem; font-weight: 600;">{{ __('Journal') }}</th>
                                <th style="padding: 0.75rem 1rem; font-weight: 600;">{{ __('Reopened') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($this->getHistory() as $row)
                                <tr style="border-top: 1px solid #f3f4f6;">
                                    <td style="padding: 0.75rem 1rem; font-weight: 600; color: #111827;">
                                        {{ \Carbon\Carbon::parse($row->start_date)->year }}
                                    </td>
                                    <td style="padding: 0.75rem 1rem;">
                                        @if($row->isClosed())
                                            <span style="display: inline-flex; border-radius: 9999px; background: #ffe4e6; color: #be123c; padding: 0.125rem 0.5rem; font-size: 0.75rem; font-weight: 600;">
                                                {{ __('Closed') }}
                                            </span>
                                        @else
                                            <span style="display: inline-flex; border-radius: 9999px; background: #d1fae5; color: #047857; padding: 0.125rem 0.5rem; font-size: 0.75rem; font-weight: 600;">
                                                {{ __('Open') }}
                                            </span>
                                        @endif
                                    </td>
                                    <td style="padding: 0.75rem 1rem; color: #4b5563;">
                                        {{ $row->closed_at?->format('d M Y H:i') ?? '-' }}
                                        @if($row->closedByUser)
                                            <div style="font-size: 0.75rem; color: #9ca3af;">{{ $row->closedByUser->name }}</div>
                                        @endif
                                    </td>
                                    <td style="padding: 0.75rem 1rem; color: #4b5563;">
                                        {{ $row->closingJournalEntry?->entry_number ?? ($row->isClosed() ? __('None') : '-') }}
                                    </td>
                                    <td style="padding: 0.75rem 1rem; color: #4b5563;">
                                        {{ $row->reopened_at?->format('d M Y H:i') ?? '-' }}
                                        @if($row->reopen_reason)
                                            <div style="font-size: 0.75rem; color: #9ca3af;">{{ $row->reopen_reason }}</div>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" style="padding: 1.5rem 1rem; text-align: center; color: #6b7280;">
                                        {{ __('No period closing history yet.') }}
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</x-filament-panels::page>
