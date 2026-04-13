# Laravel Accounting Application - Development Guide

## Commands
- `composer test` - Run full test suite (Pest)
- `./vendor/bin/pest tests/Feature/ExampleTest.php` - Run single test file
- `php artisan test --filter="test_name"` - Run specific test method
- `composer dev` - Start development server with queue, logs, and Vite
- `npm run build` - Build frontend assets
- `npm run dev` - Start Vite development server
- `php artisan migrate:fresh --seed` - Fresh database with seeders
- `php artisan queue:listen --tries=1` - Process queue jobs

## Code Style Guidelines
- Use 4 spaces for indentation (EditorConfig)
- Follow PSR-4 autoloading: `App\` namespace for `app/` directory
- Laravel 12.0 framework with Filament 4.0 for admin panels
- Pest PHP testing framework over PHPUnit
- Use strict typing with PHP 8.2+ features
- Models: Use `$fillable` arrays, `casts()` method for type casting, SoftDeletes where applicable
- Filament Resources: Separate form schemas into dedicated `Schemas\` classes, table configurations into `Tables\` classes
- Relationships: Use proper Eloquent relationship types (BelongsTo, HasMany, etc.)
- Database: PostgreSQL with migrations for schema, factories for testing, seeders for initial data
- Localization: Use `__()` function with Indonesian locale as primary (`id.json`)
- Company data isolation: Implement `HasCompanyFilter` trait for multi-tenancy
- Error handling: Use Laravel's built-in validation and exception handling
- File structure: Organize Filament resources with Pages/, Schemas/, and Tables/ subdirectories