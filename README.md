# Router
![build](https://github.com/duyler/router/workflows/build/badge.svg)

## Роутер позволяет определять маршруты для приложения вида:

`Route::get('/categories/edit/{$id}')->where(['id' => 'integer'])->handler('Catalog')->action('CategoryEdit')->name('category.edit')->match();`

## А так же генерировать ссылки с помощью имен маршрутов:

`Url::get('category.edit', ['id' => 1]);`
