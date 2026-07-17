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
                ->modalDescription(__('This will create a closing journal (P&L → Retained Earnings) and lock all transactions dated in this year.'))
                ->form([
                    TextInput::make('description')
                        ->label(__('Description'))
                        ->default(fn () => __('Tutup Buku :year', ['year' => $this->selectedYear])),
                ])
                ->action(function (array $data) {
                    $this->closeYear($data['description'] ?? null);
                })
                ->visible(fn () => $this->canCloseSelectedYear())
                ->disabled(fn () => !$this->hasCompanySelected() || !$this->hasRetainedEarningsMapping()),
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
        ];
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
            Notification::make()
                ->success()
                ->title(__('Tutup Buku completed'))
                ->body(__('Year :year closed. Journal :je', [
                    'year' => $this->selectedYear,
                    'je' => $period->closingJournalEntry?->entry_number ?? '-',
                ]))
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
