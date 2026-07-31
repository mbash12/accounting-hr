# COA Export Import Format

## Goal

Make the Chart of Accounts export directly reusable as an import file by making its spreadsheet contract identical to the existing COA import template.

## Design

`AccountsExport` will expose the same nine lowercase headings and order as `AccountsTemplateExport`:

`code`, `name`, `description`, `classification_type`, `is_header`, `is_cash_bank`, `is_active`, `level`, `parent_code`.

Exported boolean values remain `yes`/`no`, and `parent_code` is resolved from the account's parent within the selected company. Accounts remain scoped to the selected company and are ordered by account code for stable, import-friendly output.

No import behavior or database schema changes are needed.

## Verification

Add unit coverage for the export headings and mapped row values, including parent-code resolution and boolean serialization. Run the focused test and the full test suite.
