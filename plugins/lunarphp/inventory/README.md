# Inventory product management

## Description
Inventory is a fully tested, PSR compliant Laravel inventory solution. It provides the basics of inventory management
using Eloquent such as:

- Inventory product management
- Inventory product Variant management
- Inventory Stock management (per warehouse)
- Inventory Stock movement tracking
- Inventory Assembly management (Bill of Materials)
- Inventory Supplier management
- Inventory Transaction management

All movements, stocks and inventory items are automatically given the current logged in user's ID. All inventory actions
such as puts/removes/creations are covered by Laravel's built in database transactions. If any exception occurs
during an inventory change, it will be rolled back automatically.

Depending on your needs, you may use the built in traits for customizing and creating your own models, or
you can simply use the built in models.

## Installation

You can install the package via composer:

```bash
composer require hs/inventory
```

You can publish and run the migrations with:

```bash
php artisan vendor:publish --tag="inventory-migrations"
php artisan migrate
```

You can publish the config file with:

```bash
php artisan vendor:publish --tag="inventory-config"
```

Optionally, you can publish the views using

```bash
php artisan vendor:publish --tag="inventory-views"
```

Then register the plugin in your service provider

```php
use Lunar\Admin\Support\Facades\LunarPanel;
use HS\Inventory\InventoryPlugin;
// ...

public function register(): void
{
    LunarPanel::panel(function (Panel $panel) {
        return $panel->plugin(new InventoryPlugin());
    })->register();
    
    // ...
}
```

Add traits to inventory model
```php
class ProductVariant extends Model
{
    use HasInventory;
    use HasAssembly;
}
```

## Usage


## Testing

```bash
composer test
```

## Changelog

All notable changes to `inventory` will be documented in this file.

## 1.0.0 - 2024-09-08

- initial release
