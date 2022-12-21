# TailwindCSS и Vue 3 для Laravel
Базовые инструкции

## Установка

### Tailwind CSS
Основные этапы описаны в [официальной документации](https://tailwindcss.com/docs/guides/laravel).

Дополнительно устанавливаются плагины:
* `@tailwindcss/forms`: Работа с элементами форм
* `@tailwindcss/typography`: Работа с текстом (статьи, новости и т.п.)
* `@tailwindcss/aspect-ratio`: Вывод видео (например, youtube) с корректным соотношением сторон
* `@tailwindcss/line-clamp`: Обрезка текста по строкам

Установка

``
npm install -D @tailwindcss/typography @tailwindcss/forms @tailwindcss/line-clamp @tailwindcss/aspect-ratio
``

Регистрируем плагины в `tailwind.config.js`:

```js
/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
    extend: {},
    },
    plugins: [
        require('@tailwindcss/typography'),
        require('@tailwindcss/forms'),
        require('@tailwindcss/line-clamp'),
        require('@tailwindcss/aspect-ratio'),
    ],
}
```

### Vue JS

Установка

```
npm i vue@next vue-loader@next

npm i @vitejs/plugin-vue
```

Добавляем в `vite.config.js` vue. При необходимости указываем дополнительные настройки

```js
import vue from '@vitejs/plugin-vue'
```

в `plugins`:

```js
    vue({
            template: {
                transformAssetUrls: {
                    // The Vue plugin will re-write asset URLs, when referenced
                    // in Single File Components, to point to the Laravel web
                    // server. Setting this to `null` allows the Laravel plugin
                    // to instead re-write asset URLs to point to the Vite
                    // server instead.
                    base: null,

                    // The Vue plugin will parse absolute URLs and treat them
                    // as absolute paths to files on disk. Setting this to
                    // `false` will leave absolute URLs un-touched so they can
                    // reference assets in the public directly as expected.
                    includeAbsolute: false,
                },
            },
        }),
```
