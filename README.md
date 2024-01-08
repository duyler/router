# Router
![build](https://github.com/duyler/router/workflows/build/badge.svg)

## Usage routing:

```php
Route::get('/categories/edit/{$id}')
    ->where(['id' => Type::Integer])
    ->handler('Catalog')
    ->action('CategoryEdit')
    ->name('category.edit');
    
$routeCollection = new RouteCollection();
$routeCollection->add(
    Route::get('/categories/edit/{$id}')
        ->where(['id' => Type::Integer])
        ->handler('Catalog')
        ->action('CategoryEdit')
        ->name('category.edit')
);

$router = new Router();
$currentRoute = $router->startRouting($routeCollection, $request);
    
```

## Creating links from routes:

```php
Url::get('category.edit', ['id' => 1]);
```
