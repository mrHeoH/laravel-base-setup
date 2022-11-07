# Основные настройки Laravel в новом проекте

### config/app.php

Основная локаль - русская:

```php
'locale' => 'en',
```

заменить на 

```php
'locale' => 'ru',
```

Если проект русскоязычный, то тестовые данные пусть будут на русском языке:

```php
'faker_locale' => 'en_US',
```

заменить на 

```php
'faker_locale' => 'ru_RU',
```

### config/hashing.php

Если предпочтение в шифровании отдается `argon2id`, то меняем:


```php
'driver' => 'bcrypt',
```

на

```php
'driver' => 'argon2id',
```

Также в настройках argon увеличиваем количество потоков:

```php
'argon' => [
    ...
    'threads' => 2,
    ...
],
```

### app/Http/Kernel.php

Если нужен более читаемый вид json-данных в браузере:

```php
    protected $middlewareGroups = [
        'web' => [
            ...
        ],

        'api' => [
            ...
            \App\Http\Middleware\PrettyJson::class,
        ],
    ];
```

### app/Provides/AppServiceProvider.php

Ужесточаем требования к коду и проверкам в нем:

```php
    use Illuminate\Database\Eloquent\Model;
    
    ...
    
    public function boot()
    {
        ...
        Model::shouldBeStrict(!app()->isProduction());
    }
```
