<?php

namespace App\Models;

/**
 * Read-only proxy model for Receivable List views.
 * Extends Contact so filament-shield generates independent permissions,
 * while using the same underlying `contacts` table.
 */
class ReceivableContact extends Contact
{
    protected $table = 'contacts';
}
