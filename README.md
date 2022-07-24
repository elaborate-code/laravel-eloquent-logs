# laravel-eloquent-logs

![Packagist Version](https://img.shields.io/packagist/v/elaborate-code/laravel-eloquent-logs?style=for-the-badge)
![Packagist Downloads (custom server)](https://img.shields.io/packagist/dt/elaborate-code/laravel-eloquent-logs?style=for-the-badge)
![GitHub Workflow Status](https://img.shields.io/github/workflow/status/elaborate-code/laravel-eloquent-logs/run-tests?label=Tests&style=for-the-badge)
![GitHub Workflow Status](https://img.shields.io/github/workflow/status/elaborate-code/laravel-eloquent-logs/Fix%20PHP%20code%20style%20issues?label=GitHub%20Code%20Style%20Action%20Status&style=for-the-badge)


Log the changes (`created`/`updated`/`deleted`/`soft deleted`/`restored`/`force deleted`) that occurs on your Eloquent models and check which user made them and when.

## Installation

Install the package via composer:

```bash
composer require elaborate-code/laravel-eloquent-logs
```

Publish the migrations:

```bash
php artisan vendor:publish --tag="eloquent-logs-migrations"
```

Run the migrations:

```bash
php artisan migrate
```

### Publishing config file [Optional]

You can publish the config file with:

```bash
php artisan vendor:publish --tag="eloquent-logs-config"
```

This is the contents of the published config file:

```php
return [
    'logs_model' => \ElaborateCode\EloquentLogs\Models\EloquentLog::class,
    'logs_table' => 'eloquent_logs',

    /** @phpstan-ignore-next-line */
    'user' => \App\Models\User::class,

];
```

## Usage

Pick an **Eloquent model** that you want to log the changes that happen to it and add the `HasLogs` trait to it.

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Example extends Model
{
    use HasFactory, \ElaborateCode\EloquentLogs\Concerns\HasLogs; // Changed
    // ...
}
```

After adding that trait, every change made to the model will be recorded.

Important warning from [Laravel docs](https://laravel.com/docs/9.x/eloquent#events:~:text=When%20issuing%20a%20mass%20update%20or,when%20performing%20mass%20updates%20or%20deletes.)

> When issuing a **mass update or delete** query via Eloquent, the `saved`, `updated`, `deleting`, and `deleted` model events will not be dispatched for the affected models. This is because the models are never actually retrieved when performing mass updates or deletes.

### Retrieving logs

You can load a model's logs using the `eloquentLogs` relationship:

```php
$example->eloquentLogs;

$example->load('eloquentLogs');

App\Models\Example::with('eloquentLogs')->find($id);
```

And you can query logs directly:

```php
// latest 5 logs with affected models
ElaborateCode\EloquentLogs\Models\EloquentLog::with('loggable')->latest()->limit(5)->get()
```

### Muting Eloquent events

From seeders:

```php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Silent eloquent queries ...
    }
}
```

Anywhere from your code:

```php
\Illuminate\Database\Eloquent\Model::unsetEventDispatcher();

// Silent eloquent queries ...

\Illuminate\Database\Eloquent\Model::setEventDispatcher(app(Dispatcher::class));
// ...
```

Explore the [Eloquent docs](https://laravel.com/docs/9.x/eloquent#muting-events) for more options

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](https://github.com/elaborate-code/.github/blob/main/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [medilies](https://github.com/elaborate-code)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
