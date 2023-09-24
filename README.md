# Router
![build](https://github.com/duyler/router/workflows/build/badge.svg)

## Usage routing:

```php
Route::get('/categories/edit/{$id}')
    ->where(['id' => Type::Integer])
    ->handler('Catalog')
    ->action('CategoryEdit')
    ->name('category.edit')
    ->match();
```

## Creating links from routes:

```php
Url::get('category.edit', ['id' => 1]);
```
