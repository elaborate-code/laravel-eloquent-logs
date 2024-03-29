# laravel-eloquent-logs

[![Packagist Version](https://img.shields.io/packagist/v/elaborate-code/laravel-eloquent-logs?style=for-the-badge)](https://packagist.org/packages/elaborate-code/laravel-eloquent-logs)
[![Packagist Downloads (custom server)](https://img.shields.io/packagist/dt/elaborate-code/laravel-eloquent-logs?style=for-the-badge)](https://packagist.org/packages/elaborate-code/laravel-eloquent-logs)
[![GitHub Workflow Status](https://img.shields.io/github/workflow/status/elaborate-code/laravel-eloquent-logs/run-tests?label=Tests&style=for-the-badge)](https://github.com/elaborate-code/laravel-eloquent-logs/actions/workflows/run-tests.yml)
[![GitHub Workflow Status](https://img.shields.io/github/workflow/status/elaborate-code/laravel-eloquent-logs/Fix%20PHP%20code%20style%20issues?label=Code%20Style&style=for-the-badge)](https://github.com/elaborate-code/laravel-eloquent-logs/actions/workflows/fix-php-code-style-issues.yml)
![maintained](https://img.shields.io/maintenance/yes/2022)
![Production ready](https://img.shields.io/badge/Production%20ready-no-red)

![banner](https://banners.beyondco.de/Eloquent%20logs.png?theme=dark&packageManager=composer+require&packageName=elaborate-code%2Flaravel-eloquent-logs&pattern=circuitBoard&style=style_1&description=A+simple+way+to+log+changes+that+occur+on+Eloquent+models&md=1&showWatermark=0&fontSize=100px&images=https%3A%2F%2Flaravel.com%2Fimg%2Flogomark.min.svg)

Log what happens to your Eloquent models (`created`|`updated`|`deleted`|`soft deleted`|`restored`|`force deleted`) and keep and eye on **who** made the change, **how** and **when**. 

This solution is simple to integrate and introduces minimal changes to your project: 1 migration, 1 model, 1 trait, and 1 facade.

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

    'user' => \App\Models\User::class,
];
```

That allows you to rename the `logs_table` before running the migrations.

## Usage

Pick an **Eloquent model** that you want to log the changes that happen to it and add the `HasLogs` trait to it.

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExampleModel extends Model
{
    use \ElaborateCode\EloquentLogs\Concerns\HasLogs;
    // ...
}
```

After adding that trait, every change made to the model will be recorded.

Important warning from [Laravel docs](https://laravel.com/docs/9.x/eloquent#events:~:text=When%20issuing%20a%20mass%20update%20or,when%20performing%20mass%20updates%20or%20deletes.)

> When issuing a **mass update or delete** query via Eloquent, the `saved`, `updated`, `deleting`, and `deleted` model events will not be dispatched for the affected models. This is because the models are never actually retrieved when performing mass updates or deletes.

### Retrieving logs

You can load a model's logs using the `eloquentLogs` relationship:

```php
$example_model->eloquentLogs;

$example_model->load('eloquentLogs');

App\Models\ExampleModel::with('eloquentLogs')->find($id);
```

And you can query logs directly:

```php
// latest 5 logs with affected models
ElaborateCode\EloquentLogs\Models\EloquentLog::with('loggable')->latest()->limit(5)->get()
```

### Grouping queries

By default each one model event will result in a query to log the action.

```php
$example_model = ExampleModel::create(['name' => 'foo']);

$example_model->update(['name' => 'bar']);

$example_model->delete();

// ⚠️ This will result in 3 queries to insert the 3 events logs into the database  
```

You can improve the logging process by using the `CacheEloquentLogQueries` facade

```php
use ElaborateCode\EloquentLogs\Facades\CacheEloquentLogQueries;

CacheEloquentLogQueries::start();

$example_model = ExampleModel::create(['name' => 'foo']);

$example_model->update(['name' => 'bar']);

$example_model->delete();

CacheEloquentLogQueries::execute();

// 👍 This will result in 1 query to insert the 3 events logs into the database  
```

The facade includes other methods that you wouldn't necessarily need to use:

```php
// Stops caching and empties the cache without queries execution
CacheEloquentLogQueries::reset();

// Empties the cache but doesn't stop caching
CacheEloquentLogQueries::flushQueries();

// Stops caching until the reuse of start() and doesn't empty the cache
CacheEloquentLogQueries::suspend();

// Returns a boolean
CacheEloquentLogQueries::isCaching();
```

### Ignoring events

You can specify the events to not log on the model instances by listing the events to ignore on `YourModel::$loggableOptions['ignore']`.

```php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExampleModel extends Model
{
    use \ElaborateCode\EloquentLogs\Concerns\HasLogs;

    public static array $loggableOptions = [
        'ignore' => ['created', 'updated', 'deleted', 'softDeleted', 'forceDeleted', 'restored'],
    ];
    // ...
}
```

### Muting Eloquent events [Laravel stuff]

From seeders:

```php
namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents; // Add this trait

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

## Alternative

Among the bajillion packages that Spatie has so graciously bestowed upon the community, you'll find the excellent [laravel-Alternative](https://github.com/spatie/laravel-activitylog) package.

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
