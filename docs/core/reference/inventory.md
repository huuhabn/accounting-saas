# Inventory

## Overview

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
composer require lunar/inventory
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

## Product Inventory

By default store will mark all product variants as `stockable`. If you don't need to ship a certain variant then you can
simply set this to false.

```php
$variant->update([
    'stockable' => false,
]);
```
## Creating a stock

Now, we need to create a price for our variant. This is where we use our *currency* created or fetched earlier.

```php
$variant->stocks()->create([
    'stock' => 199,
    'inventory_id' => $inventory->id,
]);
```

### Exceptions

When creating variants there are some exceptions that will be thrown if certain conditions are met.

| Exception                                        | Conditions                                                                             |
|:-------------------------------------------------|:---------------------------------------------------------------------------------------|
| `Lunar\Exceptions\InvalidProductValuesException` | Thrown if you try and create a variant with less option values than what are required. |
| `Illuminate\Validation\ValidationException`      | Thrown if validation fails on the value options array.                                 |

## Stock

### Overview

Prices are stored in the database as integers. When retrieving a `Price` model the `price` and `compare_price`
attributes are cast to a `Price` datatype. This casting gives you some useful helpers when dealing with prices on your
front end.

| Field               | Description                                                                          | Default | Required |
|:--------------------|:-------------------------------------------------------------------------------------|:--------|:---------|
| `price`             | A integer value for the price                                                        | `null`  | yes      |
| `compare_price`     | For display purposes, allows you to show a comparison price, e.g. RRP.               | `null`  | no       |
| `currency_id`       | The ID of the related currency                                                       | `null`  | yes      |
| `tier`              | The lower limit to get this price, 1 is the default for base pricing.                | `1`     | no       |
| `customer_group_id` | The customer group this price relates to, leaving as `null` means any customer group | `null`  | no       |
| `priceable_type`    | This is the class reference to the related model which owns the price                | `null`  | yes      |
| `priceable_id`      | This is the id of the related model which owns the price                             | `null`  | yes      |

```php
$price = \Lunar\Models\Price::create([
    'price' => 199,
    'compare_price' => 299,
    'currency_id' => 1,
    'tier' => 1,
    'customer_group_id' => null,
    'priceable_type' => 'Lunar\Models\ProductVariant',
    'priceable_id' => 1,
]);
```

## Price formatting

For the full reference on how to format prices for your storefront see the [Pricing Reference](/core/reference/pricing)

::: tip
The same methods apply to the compare_price attribute
:::

### Base stock

Pricing is defined on a variant level, meaning you will have a different price for each variant and also for each
currency in the system. In order to add pricing to a variant, you can either create the model directly or use the
relationship method.

```php
\Lunar\Models\Price::create([
    'price' => 199,
    'compare_price' => 299,
    'currency_id' => 1,
    'tier' => 1,
    'customer_group_id' => null,
    'priceable_type' => 'Lunar\Models\ProductVariant',
    'priceable_id' => 1,
]);
```

```php
$variant->stocks()->create([/* .. */]);
```

### Warehouse stocking

You can specify which customer group the price applies to by setting the `warehouse_id` column. If left as `null`
the price will apply to all customer groups. This is useful if you want to have different pricing for certain customer
groups and also different price tiers per customer group.

### Tiered Pricing

Tiered pricing is a concept in which when you buy in bulk, the cost per item will change (usually go down). With Pricing
on Lunar, this is determined by the `tier` column when creating prices. For example:

```php
Price::create([
    // ...
    'price' => 199,
    'compare_price' => 399,
    'tier' => 1,
]);

Price::create([
    // ...
    'price' => 150,
    'compare_price' => 399,
    'tier' => 10,
]);
```

In the above example if you order between 1 and 9 items you will pay `1.99` per item. But if you order at least 10 you
will pay `1.50` per item.

## Fetching the price

Once you've got your pricing all set up, you're likely going to want to display it on your storefront. We've created
a `PricingManager` which is available via a facade to make this process as painless as possible.

To get the pricing for a product you can simple use the following helpers:

#### Minimum example

A quantity of 1 is implied when not passed.

```php
$stocking = \App\Facades\Stocking::for($variant)->get();
```

#### With Quantities

```php
$stocking = \App\Facades\Stocking::qty(5)->for($variant)->get();
```

#### With Warehouses

If you don't pass in a warehouse, Lunar will use the default, including any pricing that isn't specific to a
warehouse.

```php
$stocking = \App\Facades\Stocking::warehouses($groups)->for($variant)->get();

// Or a single warehouse
$stocking = \App\Facades\Stocking::warehouse($group)->for($variant)->get();
```

#### Specific to a user

The PricingManager assumes you want the price for the current authenticated user.

If you want to always return the guest price, you may use...

```php
$stocking = \App\Facades\Stocking::guest()->for($variant)->get();
```

Or to specify a different user...

```php
$stocking = \App\Facades\Stocking::user($user)->for($variant)->get();
```

#### With a specific currency

If you don't pass in a currency, the default is implied.

```php
$stocking = \App\Facades\Stocking::currency($currency)->for($variant)->get();
```

#### For a model

Assuming you have a model that implements the `hasStocks` trait, such as a `ProductVariant`, you can use the following
to retrieve pricing.

```php
$pricing = $variant->pricing()->qty(5)->get();
```

::: danger Be aware
If you try and fetch a price for a currency that doesn't exist, a ` Lunar\Exceptions\MissingCurrencyPriceException`
exception will be thrown.
:::

---

This will return a `PricingResponse` object which you can interact with to display the correct prices. Unless it's a
collection, each property will return a `Lunar\Models\Price` object.

```php
/**
 * The price that was matched given the criteria.
 */
$pricing->matched;

/**
 * The base price associated to the variant.
 */
$pricing->base;

/**
 * A collection of all the price tiers available for the given criteria.
 */
$pricing->tiers;

/**
 * All customer group pricing available for the given criteria.
 */
$pricing->customerGroupPrices;
```

Sometimes you might want to simply get all prices a product has from the variants, instead of loading up all a products
variants fetching the prices that way, you can use the `prices` relationship on the product.

```php
$product->prices
```

This will return a collection of `Price` models.

### Storing Prices Inclusive of Tax

Lunar allows you to store pricing inclusive of tax if you need to. This is helpful if you need to show charm pricing, at
$9.99 for example, which may not be possible if pricing is stored exclusive of tax due to rounding.

To start you will need to set the `stored_inclusive_of_tax` config value in `lunar/pricing` to `true`. Then you will
need to ensure your default Tax Zone is set up correctly with the correct tax rates.

Once set, the cart will automatically calculate the tax for you.

If you need to show both ex. and inc. tax pricing on your product pages, you can use the following methods which are
available on the `Lunar\Models\Price` model.

```php
$price->priceIncTax();
$price->priceExTax();
```

### Customising Prices with Pipelines

All pipelines are defined in `config/lunar/pricing.php`

```php
'pipelines' => [
    //,
],
```

You can add your own pipelines to the configuration, they might look something like:

```php
<?php

namespace App\Pipelines\Pricing;

use Closure;
use Lunar\Base\PricingManagerInterface;

class CustomPricingPipeline
{
    public function handle(PricingManagerInterface $pricingManager, Closure $next)
    {
        $matchedPrice = $pricingManager->pricing->matched;

        $matchedPrice->price->value = 200;

        $pricingManager->pricing->matched = $matchedPrice;

        return $next($pricingManager);
    }
}
```

```php
'pipelines' => [
    // ...
    App\Pipelines\Pricing\CustomPricingPipeline::class,
],
```

::: tip
Pipelines will run from top to bottom
:::

## Full Example

For this example, we're going to be creating some Dr. Martens boots. Below is a screenshot of what we're aiming for:

![](/images/products/dr-martens.png)

Here are the steps we're going to take:

- Create our product type
- Create the initial product
- Create the product options and their values
- Generate the variants based on those values

### Set up the product type.

```php
$productType = Lunar\Models\ProductType::create([
    'name' => 'Boots',
]);
```

::: tip Note
This example assumes we already have Attributes set up for name and description and that they're assigned to the product
type.
:::

### Create the initial product

```php
Lunar\Models\Product::create([
    'product_type_id' => $productType->id,
    'status' => 'published',
    'brand_id' => $brandId,
    'sku' => 'DRBOOT',
    'attribute_data' => [
        'name' => new TranslatedText(collect([
            'en' => new Text('1460 PATENT LEATHER BOOTS'),
        ])),
        'description' => new Text('Even more shades from our archive...'),
    ],
]);
```

### Product Options

Based on the example above we're going to need 2 options, Size and Colour.

```php
$colour = Lunar\Models\ProductOption::create([
    'name' => [
        'en' => 'Colour',
    ],
    'label' => [
        'en' => 'Colour',
    ],
]);

$size = Lunar\Models\ProductOption::create([
    'name' => [
        'en' => 'Size',
    ],
    'label' => [
        'en' => 'Size',
    ],
]);
```

### Product Option Values

From here we now need to create our option values like so:

```php
$colour->values()->createMany([
    [
        'name' => [
            'en' => 'Black',
        ],
    ],
    [
        'name' => [
            'en' => 'White',
        ],
    ],
    [
        'name' => [
            'en' => 'Pale Pink',
        ],
    ],
    [
        'name' => [
            'en' => 'Mid Blue',
        ],
    ],
]);

// We won't do all the sizes here, just enough to get the idea...
$size->values()->createMany([
    [
        'name' => [
            'en' => '3',
        ],
    ],
    [
        'name' => [
            'en' => '6',
        ],
    ],
]);
```

### Generate the variants

First we just need to grab the values we want to use to generate the variants. Since we're generating them for
everything, we just grab all of them.

```php
$optionValueIds = $size->values->merge($colour->values)->pluck('id');

\Lunar\Hub\Jobs\Products\GenerateVariants::dispatch($product, $optionValueIds);
```

::: tip
When generating variants, the sku will be derived from the Product's base SKU, in this case `DRBOOT` and will be
suffixed with `-{count}`.
:::

The resulting generation is as follows:

| SKU      | Colour    | Size |
|:---------|:----------|:-----|
| DRBOOT-1 | Black     | 3    |
| DRBOOT-2 | Black     | 6    |
| DRBOOT-3 | White     | 3    |
| DRBOOT-4 | White     | 6    |
| DRBOOT-5 | Pale Pink | 3    |
| DRBOOT-6 | Pale Pink | 6    |
| DRBOOT-7 | Mid Blue  | 3    |
| DRBOOT-8 | Mid Blue  | 6    |

You are then free to change the SKU's as you see fit, update the pricing for each variant etc before publishing.
