# This is my package filament-advanced

This is where your description should go. Limit it to a paragraph or two. Consider adding a small example.

## Installation

You can install the package via composer:

```bash
composer require huuhabn/filament-advanced
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="filament-advanced-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="filament-advanced-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="filament-advanced-views"
```

Publish the translations using:

```bash
php artisan vendor:publish --tag="filament-advanced-translations"
```
Publish the assets using:

```bash
php artisan vendor:publish --tag="filament-advanced-assets"
```

## Usage

### Background auth page

#### Add the plugin to your panel provider:
```php
namespace App\Providers\Filament;

use HanaSales\FilamentAdvanced\FilamentBackgroundsPlugin;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->plugins([
                FilamentBackgroundsPlugin::make()
                ->bgType('svg'), // default jpg
            ])
    }
}

```

### Impersonate

#### Add table action
```php
namespace App\Filament\Resources;
 
use Filament\Resources\Resource;
use HanaSales\FilamentAdvanced\Tables\Actions\Impersonate;
 
class UserResource extends Resource {
    public static function table(Table $table)
    {
        return $table
            //
            ->actions([
                Impersonate::make();
                //->guard('another-guard')
                //->redirectTo(route('some.other.route'));
            ]);
    }
}
```

#### Add the page action
```php
namespace App\Filament\Resources\UserResource\Pages;
 
use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\EditRecord;
use HanaSales\FilamentAdvanced\Pages\Actions\Impersonate;
 
class EditUser extends EditRecord
{
    protected function getActions(): array
    {
        return [
            Impersonate::make()->record($this->getRecord())
        ];
    }
}
```

### Side by side combobox multiselect field.
```php
use HanaSales\FilamentAdvanced\Forms\Fields\Combobox;
 
class PostResource extends Resource
{
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
                Combobox::make('categories')
                ->boxSearchs(true) // Default: false
                ->relationship('categories', 'name')
                ->optionsLabel('Available categories')
                ->selectedLabel('Selected categories')
                ->showLabels() // Default: true
            ]);
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

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [CyberSilk](https://github.com/huuhabn)
- [All Contributors](../../contributors)
