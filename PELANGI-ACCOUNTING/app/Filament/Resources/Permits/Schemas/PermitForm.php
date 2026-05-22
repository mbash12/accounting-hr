<?php

namespace App\Filament\Resources\Permits\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class PermitForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make(__('Permit / Leave Submission'))
                    ->schema([
                        Select::make('employee_id')
                            ->label(__('Employee'))
                            ->relationship(
                                name: 'employee', 
                                titleAttribute: 'name',
                                modifyQueryUsing: function ($query) {
                                    $companyId = session('selected_company_id');
                                    if ($companyId) {
                                        $query->where('company_id', $companyId);
                                    } elseif (auth()->check()) {
                                        $ids = auth()->user()->companies()->pluck('companies.id');
                                        if ($ids->isNotEmpty()) $query->whereIn('company_id', $ids);
                                    }
                                }
                            )
                            ->required()
                            ->searchable()
                            ->preload(),
                        Select::make('type')
                            ->label(__('Type'))
                            ->options([
                                'sick' => __('Sick'),
                                'annual_leave' => __('Annual Leave (Legacy)'),
                                'unpaid_leave' => __('Unpaid Leave (Legacy)'),
                                'maternity_leave' => __('Maternity Leave (Legacy)'),
                                'other_permit' => __('Other Permit (Legacy)'),
                                'annual' => __('Annual Leave'),
                                'marry' => __('Marriage Leave'),
                                'kids_marry' => __('Child Marriage Leave'),
                                'khitan' => __('Child Circumcision/Baptism Leave'),
                                'family_death' => __('Immediate Family Bereavement Leave'),
                                'maternity' => __('Maternity Leave'),
                                'maternity_husband' => __('Paternity Leave'),
                                'maternity_death' => __('Miscarriage Leave'),
                                'force_majure' => __('Force Majeure / Natural Disaster'),
                                'nodn_sick' => __('Sick Without Certificate'),
                                'sudden' => __('Emergency Leave'),
                                'others' => __('Permit'),
                            ])
                            ->required(),
                        Select::make('status')
                            ->label(__('Status'))
                            ->options([
                                'pending' => __('Pending'),
                                'approved' => __('Approved'),
                                'rejected' => __('Rejected'),
                            ])
                            ->default('pending')
                            ->required(),
                        DatePicker::make('start_date')
                            ->label(__('Start Date'))
                            ->required(),
                        DatePicker::make('end_date')
                            ->label(__('End Date'))
                            ->required(),
                        FileUpload::make('attachment_path')
                            ->label(__('Attachment (Doctor Certificate, etc.)'))
                            ->directory('permits')
                            ->columnSpan(1),
                        Textarea::make('reason')
                            ->label(__('Reason'))
                            ->columnSpanFull(),
                    ])
                    ->columns(3)
                    ->columnSpanFull(),
            ]);
    }
}
