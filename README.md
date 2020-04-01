# Nova Thaana Menu Builder
This package forked from [Nova Menu Builder](https://github.com/Energon7/nova-menu-builder)
This package supports thaana keyboard by [TK](https://github.com/jawish/jtk)


This package uses [Spatie Translatable](https://github.com/spatie/laravel-translatable)

 
This tool allows you to create menus in Laravel Nova

<img width="1439" alt="menu builder Home" src="https://user-images.githubusercontent.com/42798230/50765532-7632ea80-1276-11e9-8fed-ec1f6d53983a.png">


<img width="1439" alt="Menu Builder Items" src="https://user-images.githubusercontent.com/42798230/50765390-06bcfb00-1276-11e9-9e82-fd7956507c78.png">


## Installation

You can install the package in to a Laravel app that uses [Nova](https://nova.laravel.com) via composer:

```bash
composer require aiman/nova-menu-builder
```

Then you should publish the service provider, migrate database:

```bash
php artisan vendor:publish --provider="Energon7\MenuBuilder\MenuBuilderServiceProvider"
php artisan migrate
```
Then create your own table "languages"

## Usage

Next up, you must register the tool with Nova. This is typically done in the tools method of the NovaServiceProvider.

```php
// in app/Providers/NovaServiceProvider.php

// ...

public function tools()
{
    return [
        // ...
        new \Energon7\MenuBuilder\MenuBuilder(),
    ];
}
```

## Helpers

There are two helpers built in for your blades

* **menu_builder('slug')**.    

	Creates an html menu for given slug. Extra options are not required. By default tags are `ul` and `li`, and without html classes.

```php
{!! menu_builder('main') !!}

//or

{!! menu_builder('main', 'parent-class', 'child-class',with children(boolean) , with active/deactive(boolean), 'dl', 
'dd') !!}

booleans default is true
```

* **menu_json('slug')**.   

	Returns a json with all items for given slug.

```php
{!! menu_json('main') !!}
```

* **menu_type('type')**.   

	Returns a json with all items for given type
	| Param          | 
	| -------------- |
	| main_menu      | 
	| secondary_menu | 
	| vertical_menu  | 
	| currency_menu  | 
	| langauge_menu  | 

```php
{!! menu_type('main_menu') !!}
```


## Localization

Set your translations in the corresponding xx.json file located in /resources/lang/vendor/nova


```json
"Menu Builder": "Menu Builder",
"Menu Items": "Menu Items",
"Add item": "Add item",
"Delete item": "Delete item",
"Are you sure to delete this menu item?": "Are you sure to delete this menu item?",
"Take care. All children items will be deleted cause you're deleting the parent.": "Take care. All children items will be deleted cause you're deleting the parent.",
"Cancel": "Cancel",
"Yes, remove!": "Yes, remove!",
"Add Menu item": "Add Menu item",
"Name": "Name",
"Slug": "Slug",
"Menu Helper": "Menu Helper",
"Link type": "Link type",
"Choose an option": "Choose an option",
"Static Url": "Static Url",
"Dynamic Route": "Dynamic Route",
"URL": "URL",
"Route": "Route",
"Parameters": "Parameters",
"Open in": "Open in",
"Same window": "Same window",
"New window": "New window",
"Classes": "Classes",
"Create menu item": "Create menu item",
"Update menu item": "Update menu item",
"Item removed successfully!": "Item removed successfully!",
"Item created!": "Item created!",
"Item updated!": "Item updated!",
"Menu reordered!": "Menu reordered!",
"Error on server!": "Error on server!",
"Enabled": "Enabled",
"Disabled": "Disabled"
```

## Important
This package is tested for **Nova**


## Credit
Huge Credit goes for [@Jawish Hameed](https://github.com/jawish) for his thaana translation plugin [Thaana Keyboard](https://github.com/jawish/jtk)
