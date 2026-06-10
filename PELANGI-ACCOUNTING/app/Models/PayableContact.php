<?php

namespace App\Models;

/**
 * Read-only proxy model for Accounts Payable views.
 * Extends Contact so filament-shield generates independent permissions,
 * while using the same underlying `contacts` table.
 */
class PayableContact extends Contact
{
    protected $table = 'contacts';
}
