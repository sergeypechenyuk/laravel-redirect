Laravel redirects
=================

[![Latest Stable Version](https://poser.pugx.org/sergeypechenyuk/laravel-redirect/v/stable)](https://packagist.org/packages/sergeypechenyuk/laravel-redirect)
[![Total Downloads](https://poser.pugx.org/sergeypechenyuk/laravel-redirect/downloads)](https://packagist.org/packages/sergeypechenyuk/laravel-redirect)
[![License](https://poser.pugx.org/sergeypechenyuk/laravel-redirect/license)](https://packagist.org/packages/sergeypechenyuk/laravel-redirect)
[![Monthly Downloads](https://poser.pugx.org/sergeypechenyuk/laravel-redirect/d/monthly)](https://packagist.org/packages/sergeypechenyuk/laravel-redirect)

Пакет для удобного создания редиректов в Вашем проекте



# Установка
Установка пакета с помощью Composer.

```
composer require sergeypechenyuk/laravel-redirect
```

Добавьте в файл `config/app.php` вашего проекта в конец массива `providers` :

```php
PSV\Widgets\WidgetServiceProvider::class,
```

После этого выполните в консоли команду публикации нужных ресурсов:

```
php artisan vendor:publish --provider="PSV\Widgets\WidgetServiceProvider"
```

# Использование

В файле `config\redirect.php` находится массив с установочными параметрами по умолчанию. В частности Вы можете установить дефолтный код ответа сервера, чтобы не вводить его каждый раз при добавлении нового редиректа. 

Работа с модулем происходит через командную строку `artisan`. Существует несколько команд:

#### Создание нового редиректа
```
php artisan redirect:create "hello" "hello-world-2" "302"
```
Команда `redirect:create` может принимать 4 параметра
* source *(обязательно)*: URL источника
* destination *(обязательно)*: URL назначения
* code *(опционально)*: код сервера, может принимать значения 301 и 302. Если не задано, то берется из файла конфигурации `config/redirect.php`
* expired *(опционально)*: метка времени в формате Y-m-d H:i:s до которой редирект будет действовать. Если дата уже наступила, то данные редиректа будут игнорироваться


#### Редактирование существующего редиректа
```
php artisan redirect:update "hello" "hello-world" "302" "2019-12-31 23:59:59"
```
Параметры команты `redirect:update` аналогичны команде `redirect:create`. Поиск существующего редиректа происходит по параметру `source`, если запись с таким `source` не найдена, то выведется соответствующая ошибка.


#### Удаление существующего редиректа
```
php artisan redirect:remove "hello"
```
Команда `redirect:remove` принимает только один параметр `source`, именно по этому параметру происходит поиск в таблице редиректов. Если запись с таким `source`не найдена, то выведется соответствующая ошибка. 


#### Список существующих редиректов
```
php artisan redirect:list 
```
Команда `redirect:list` может принимает только один необязательный параметр `source`, который идет в качестве поисковой строки по вхождению. Если параметр не задан, то выведется весь список редиректов.
 



