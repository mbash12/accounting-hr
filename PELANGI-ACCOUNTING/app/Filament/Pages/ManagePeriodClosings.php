<?php

namespace App\Filament\Pages;

use App\Models\AccountMapping;
use App\Models\PeriodClosing;
use App\Services\PeriodClosingService;
use BezhanSalleh\FilamentShield\Traits\HasPageShield;
use Filament\Actions\Action;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\ValidationException;
use UnitEnum;

class ManagePeriodClosings extends Page
{
    use HasPageShield;

    protected static ?string $navigationLabel = 'Tutup Buku';

    protected static string|UnitEnum|null $navigationGroup = 'General Ledger';

    protected static ?int $navigationSort = 3;

    protected static ?string $title = 'Tutup Buku';

    protected string $view = 'filament.pages.manage-period-closings';

    public int $selectedYear;

    public function mount(): void
    {
        $this->selectedYear = (int) now()->year;
    }

    public static function getNavigationLabel(): string
    {
        return __('Tutup Buku');
    }

    public static function getNavigationGroup(): ?string
    {
        return __('General Ledger');
    }

    public function getTitle(): string
    {
        return __('Tutup Buku');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('prev_year')
                ->label(__('Previous Year'))
                ->icon('heroicon-o-chevron-left')
                ->color('gray')
                ->action(fn () => $this->selectedYear--),
            Action::make('next_year')
                ->label(__('Next Year'))
                ->icon('heroicon-o-chevron-right')
                ->color('gray')
                ->action(fn () => $this->selectedYear++),
            Action::make('close_year')
                ->label(__('Tutup Buku'))
                ->icon('heroicon-o-lock-closed')
                ->color('danger')
                ->requiresConfirmation()
                ->modalHeading(fn () => __('Tutup Buku :year', ['year' => $this->selectedYear]))
                ->modalDescription(fn () => $this->closeModalDescription())
                ->modalContent(fn () => $this->closeModalPreviewContent())
                ->form([
                    TextInput::make('description')
                        ->label(__('Description'))
                        ->default(fn () => __('Tutup Buku :year', ['year' => $this->selectedYear])),
                ])
                ->action(function (array $data) {
                    $this->closeYear($data['description'] ?? null);
                })
                ->visible(fn () => $this->canCloseSelectedYear())
                ->disabled(fn () => !$this->hasCompanySelected()
                    || !$this->hasRetainedEarningsMapping()
                    || $this->getUnpostedCount() > 0),
            Action::make('reopen_year')
                ->label(__('Buka Kembali'))
                ->icon('heroicon-o-lock-open')
                ->color('warning')
                ->requiresConfirmation()
                ->modalHeading(fn () => __('Reopen :year', ['year' => $this->selectedYear]))
                ->modalDescription(__('This will remove the closing journal and unlock the year.'))
                ->form([
                    Textarea::make('reopen_reason')
                        ->label(__('Reason'))
                        ->required()
                        ->rows(3),
                ])
                ->action(function (array $data) {
                    $this->reopenYear($data['reopen_reason'] ?? '');
                })
                ->visible(fn () => $this->canReopenSelectedYear())
                ->disabled(fn () => !$this->hasCompanySelected()),
            $this->viewClosingJournalAction(),
        ];
    }

    protected function viewClosingJournalAction(): Action
    {
        return Action::make('view_closing_journal')
            ->label(__('View Closing Journal'))
            ->icon('heroicon-o-document-text')
            ->color('info')
            ->modalHeading(__('Journal Voucher'))
            ->modalWidth('6xl')
            ->modalSubmitAction(false)
            ->modalCancelActionLabel(__('Close'))
            ->visible(fn () => (bool) $this->getYearClosing()?->closingJournalEntry)
            ->modalContent(function () {
                $journalEntry = $this->getYearClosing()?->closingJournalEntry;

                if (!$journalEntry) {
                    return view('filament.actions.no-journal-voucher', [
                        'message' => __('No closing journal for this year.'),
                    ]);
                }

                $journalEntry->load([
                    'items.account',
                    'items.costCenter',
                    'department',
                    'company',
                    'postedByUser',
                    'createdByUser',
                ]);

                return view('filament.actions.journal-voucher-detail', [
                    'journalEntry' => $journalEntry,
                ]);
            });
    }

    protected function closeModalDescription(): string
    {
        $companyId = $this->companyId();
        if (!$companyId) {
            return __('Select a company first.');
        }

        $svc = app(PeriodClosingService::class);
        $unposted = $svc->countUnpostedJournals($companyId, $this->selectedYear);

        if ($unposted > 0) {
            return __('Cannot close yet: :count unposted journal(s) in :year. Post them in Posting Center first.', [
                'count' => $unposted,
                'year' => $this->selectedYear,
            ]);
        }

        try {
            $lines = $svc->previewClosingLines($companyId, $this->selectedYear);
        } catch (ValidationException) {
            return __('This will lock all transactions dated in this year.');
        }

        if ($lines === []) {
            return __('No posted P&L balances found. The year will be locked without creating a closing journal.');
        }

        return __('This will post a closing journal (P&L → Retained Earnings) and lock all transactions dated in this year.');
    }

    protected function closeModalPreviewContent(): HtmlString
    {
        $companyId = $this->companyId();
        if (!$companyId || !$this->hasRetainedEarningsMapping()) {
            return new HtmlString('');
        }

        $svc = app(PeriodClosingService::class);
        if ($svc->countUnpostedJournals($companyId, $this->selectedYear) > 0) {
            return new HtmlString(
                '<div style="margin-top:0.75rem;padding:0.75rem;border-radius:0.5rem;background:#fff1f2;color:#9f1239;font-size:0.875rem;">'
                . e(__('Close is blocked until all journals in this year are posted.'))
                . '</div>'
            );
        }

        try {
            $lines = $svc->previewClosingLines($companyId, $this->selectedYear);
        } catch (ValidationException $e) {
            return new HtmlString(
                '<div style="margin-top:0.75rem;color:#9f1239;font-size:0.875rem;">'
                . e(collect($e->errors())->flatten()->first() ?? '')
                . '</div>'
            );
        }

        if ($lines === []) {
            return new HtmlString(
                '<div style="margin-top:0.75rem;padding:0.75rem;border-radius:0.5rem;background:#fffbeb;color:#92400e;font-size:0.875rem;">'
                . e(__('Preview: no closing lines (lock only).'))
                . '</div>'
            );
        }

        $rows = '';
        $totalDebit = 0.0;
        $totalCredit = 0.0;
        foreach ($lines as $line) {
            $totalDebit += $line['debit'];
            $totalCredit += $line['credit'];
            $rows .= '<tr>'
                . '<td style="padding:0.35rem 0.5rem;border-top:1px solid #e5e7eb;">' . e($line['account_code']) . '</td>'
                . '<td style="padding:0.35rem 0.5rem;border-top:1px solid #e5e7eb;">' . e($line['account_name']) . '</td>'
                . '<td style="padding:0.35rem 0.5rem;border-top:1px solid #e5e7eb;text-align:right;">' . ($line['debit'] > 0 ? number_format($line['debit'], 2) : '-') . '</td>'
                . '<td style="padding:0.35rem 0.5rem;border-top:1px solid #e5e7eb;text-align:right;">' . ($line['credit'] > 0 ? number_format($line['credit'], 2) : '-') . '</td>'
                . '</tr>';
        }

        $html = '<div style="margin-top:0.75rem;max-height:240px;overflow:auto;border:1px solid #e5e7eb;border-radius:0.5rem;">'
            . '<table style="width:100%;border-collapse:collapse;font-size:0.8rem;">'
            . '<thead><tr style="background:#f9fafb;text-align:left;">'
            . '<th style="padding:0.35rem 0.5rem;">' . e(__('Code')) . '</th>'
            . '<th style="padding:0.35rem 0.5rem;">' . e(__('Account')) . '</th>'
            . '<th style="padding:0.35rem 0.5rem;text-align:right;">' . e(__('Debit')) . '</th>'
            . '<th style="padding:0.35rem 0.5rem;text-align:right;">' . e(__('Credit')) . '</th>'
            . '</tr></thead><tbody>' . $rows
            . '<tr style="font-weight:600;background:#f9fafb;">'
            . '<td colspan="2" style="padding:0.35rem 0.5rem;">' . e(__('Total')) . '</td>'
            . '<td style="padding:0.35rem 0.5rem;text-align:right;">' . number_format($totalDebit, 2) . '</td>'
            . '<td style="padding:0.35rem 0.5rem;text-align:right;">' . number_format($totalCredit, 2) . '</td>'
            . '</tr></tbody></table></div>';

        return new HtmlString($html);
    }

    public function hasCompanySelected(): bool
    {
        $companyId = session('selected_company_id');

        return $companyId && $companyId !== 'all';
    }

    public function companyId(): ?int
    {
        if (!$this->hasCompanySelected()) {
            return null;
        }

        return (int) session('selected_company_id');
    }

    public function hasRetainedEarningsMapping(): bool
    {
        $companyId = $this->companyId();
        if (!$companyId) {
            return false;
        }

        return (bool) AccountMapping::getAccountMapping('period_closing', 'retained_earnings', $companyId);
    }

    public function getYearClosing(): ?PeriodClosing
    {
        $companyId = $this->companyId();
        if (!$companyId) {
            return null;
        }

        return app(PeriodClosingService::class)->findYearClosing($companyId, $this->selectedYear);
    }

    public function canCloseSelectedYear(): bool
    {
        $closing = $this->getYearClosing();

        return !$closing || !$closing->isClosed();
    }

    public function canReopenSelectedYear(): bool
    {
        return (bool) $this->getYearClosing()?->isClosed();
    }

    public function getUnpostedCount(): int
    {
        $companyId = $this->companyId();
        if (!$companyId) {
            return 0;
        }

        return app(PeriodClosingService::class)->countUnpostedJournals($companyId, $this->selectedYear);
    }

    public function getHistory(): array
    {
        $companyId = $this->companyId();
        if (!$companyId) {
            return [];
        }

        return PeriodClosing::query()
            ->with(['closedByUser', 'reopenedByUser', 'closingJournalEntry'])
            ->where('company_id', $companyId)
            ->where('period_type', PeriodClosing::TYPE_YEARLY)
            ->orderByDesc('start_date')
            ->limit(20)
            ->get()
            ->all();
    }

    public function closeYear(?string $description = null): void
    {
        $companyId = $this->companyId();
        if (!$companyId) {
            Notification::make()->danger()->title(__('Select a company first.'))->send();

            return;
        }

        try {
            $period = app(PeriodClosingService::class)->closeYear($companyId, $this->selectedYear, $description);
            $je = $period->closingJournalEntry?->entry_number;

            Notification::make()
                ->success()
                ->title(__('Tutup Buku completed'))
                ->body($je
                    ? __('Year :year closed. Journal :je', ['year' => $this->selectedYear, 'je' => $je])
                    : __('Year :year locked with no closing journal (no posted P&L balances).', ['year' => $this->selectedYear])
                )
                ->send();
        } catch (ValidationException $e) {
            Notification::make()
                ->danger()
                ->title(__('Cannot close year'))
                ->body(collect($e->errors())->flatten()->first())
                ->send();
        } catch (\Throwable $e) {
            Notification::make()
                ->danger()
                ->title(__('Cannot close year'))
                ->body($e->getMessage())
                ->send();
        }
    }

    public function reopenYear(string $reason): void
    {
        $companyId = $this->companyId();
        $closing = $this->getYearClosing();

        if (!$companyId || !$closing) {
            Notification::make()->danger()->title(__('No closed year found.'))->send();

            return;
        }

        try {
            app(PeriodClosingService::class)->reopenYear($closing, $reason);
            Notification::make()
                ->success()
                ->title(__('Year reopened'))
                ->body(__('Fiscal year :year is open again.', ['year' => $this->selectedYear]))
                ->send();
        } catch (ValidationException $e) {
            Notification::make()
                ->danger()
                ->title(__('Cannot reopen year'))
                ->body(collect($e->errors())->flatten()->first())
                ->send();
        } catch (\Throwable $e) {
            Notification::make()
                ->danger()
                ->title(__('Cannot reopen year'))
                ->body($e->getMessage())
                ->send();
        }
    }
}
