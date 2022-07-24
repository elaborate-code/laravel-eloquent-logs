# This is my package laravel-eloquent-logs

[![Latest Version on Packagist](https://img.shields.io/packagist/v/elaborate-code/laravel-eloquent-logs.svg?style=flat-square)](https://packagist.org/packages/elaborate-code/laravel-eloquent-logs)
[![GitHub Tests Action Status](https://img.shields.io/github/workflow/status/elaborate-code/laravel-eloquent-logs/run-tests?label=tests)](https://github.com/elaborate-code/laravel-eloquent-logs/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/workflow/status/elaborate-code/laravel-eloquent-logs/Check%20&%20fix%20styling?label=code%20style)](https://github.com/elaborate-code/laravel-eloquent-logs/actions?query=workflow%3A"Check+%26+fix+styling"+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/elaborate-code/laravel-eloquent-logs.svg?style=flat-square)](https://packagist.org/packages/elaborate-code/laravel-eloquent-logs)

...

## Installation

You can install the package via composer:

```bash
composer require elaborate-code/laravel-eloquent-logs
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="eloquent-logs-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="eloquent-logs-config"
```

This is the contents of the published config file:

```php
return [
    'logs_model' => \ElaborateCode\EloquentLogs\EloquentLog::class,
    'logs_table' => 'eloquent_logs',

    /** @phpstan-ignore-next-line */
    'user' => \App\Models\User::class,

];
```

## Usage

Pick an **Eloquent model** that you want to log the changes that happen to it and add the `HasLogs` trait to it.

```php
namespace App\Models;

use ElaborateCode\EloquentLogs\Concerns\HasLogs; // Changed
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Example extends Model
{
    use HasFactory, HasLogs; // Changed
    // ...
}
```

Run the migrations to create the `eloquent_logs` table.

### Muting Eloquent events

You may wish to ignore the events at some part of your code, for example while seeding your application for development. You can achieve that buy using `ModelClass::unsetEventDispatcher()`.

```php
class DatabaseSeeder extends Seeder
{
    public function run()
    {
        foreach ([
            Post::class,
            Comment::class,
        ] as $model) {
            $model::unsetEventDispatcher();
        }

        // ...
    }
}
```

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
