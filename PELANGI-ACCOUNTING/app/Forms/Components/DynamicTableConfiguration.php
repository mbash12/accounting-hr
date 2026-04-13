<?php

namespace App\Forms\Components;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DynamicTableConfiguration
{
    protected array $columns = [];
    protected array $searchColumns = [];
    protected ?\Closure $modifyQueryUsing = null;
    
    public function __construct(array $columns, array $searchColumns = [], ?\Closure $modifyQueryUsing = null)
    {
        $this->columns = $columns;
        $this->searchColumns = $searchColumns;
        $this->modifyQueryUsing = $modifyQueryUsing;
    }
    
    public static function create(array $columns, array $searchColumns = [], ?\Closure $modifyQueryUsing = null): self
    {
        return new self($columns, $searchColumns, $modifyQueryUsing);
    }
    
    public static function configure(Table $table): Table
    {
        // This will be called with the instance columns
        return $table;
    }
    
    public function configureTable(Table $table): Table
    {
        $table = $table->columns($this->columns);
        
        if (!empty($this->searchColumns)) {
            foreach ($this->searchColumns as $column) {
                $table = $table->searchable($column);
            }
        }
        
        if ($this->modifyQueryUsing) {
            $table = $table->modifyQueryUsing($this->modifyQueryUsing);
        }
        
        return $table;
    }
    
    public function getColumns(): array
    {
        return $this->columns;
    }
    
    public function getSearchColumns(): array
    {
        return $this->searchColumns;
    }
    
    public function getModifyQueryUsing(): ?\Closure
    {
        return $this->modifyQueryUsing;
    }
}