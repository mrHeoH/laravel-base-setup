# Базовая настройка для новых проектов на Laravel 11

## Настройки для локальной разработки

Часть настроек указывает на работу по протоколу **https в локальной среде**. 

### .env

```
APP_LOCALE=ru
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=ru_RU

APP_FORCE_HTTP=true
```

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=app
DB_USERNAME=app
DB_PASSWORD=secret
```

```
SESSION_DRIVER=file
SESSION_LIFETIME=120
```

```
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
```

### app/Providers/AppServiceProvider.php

```php
    use Illuminate\Database\Eloquent\Model;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\URL;
    use Illuminate\Validation\Rules\Password;

    ...

    public function boot(Request $request): void
    {
        // Строгие правила работы с кодом для сайта не в продакшене
        Model::shouldBeStrict(!app()->isProduction());

        // Принудительное включение https на локальном сервере
        if (getenv('APP_FORCE_HTTP')) {
            URL::forceScheme('https');
        }

        // Стоит ли показывать счетчики на странице
        $showCounters = true;
        $userAgent = $request->header('User-Agent');
        
        // Для Lighthouse по умолчанию не показывать
        if (str_contains($userAgent, 'Chrome-Lighthouse') || config('app.debug')) {
            $showCounters = false;
        }

        view()->share('core_show_counters', $showCounters);

        // Правила валидации пароля
        Password::defaults(function () {
            $rule = Password::min(8);
            
            // Для прода более строгие правила
            return $this->app->isProduction()
                ? $rule->mixedCase()->numbers()->symbols()
                : $rule;
        });
    }
```

### bootstrap/app.php

Если используются middleware для форматирования выходящих данных:

```php
use App\Http\Middleware\SanitizeOutput;

...

return Application::configure(basePath: dirname(__DIR__))
    ...
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(SanitizeOutput::class);
    })
```


### vite.config.js

Установка:

```
npm install -D sass
```

Добавление поддержки SCSS:

```
import sass from 'sass'
```

На том же уровне, что и plugins:

```
    css: {
        preprocessorOptions: {

            scss: {
                implementation: sass,
                api: 'modern-compiler'
            },
        },
    }
```

### tailwind.config.js

Установка плагинов для Tailwind CSS v.2

```
npm install -D @tailwindcss/typography @tailwindcss/forms @tailwindcss/aspect-ratio
```

Добавляем на том же уровне, что и content:

```
    plugins: [
        require('@tailwindcss/typography'),
        require('@tailwindcss/forms'),
        require('@tailwindcss/aspect-ratio'),
    ],
```
