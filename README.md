<h1 align="center">Menvel-Notification</h1>

Menvel-Notification is a notification helper for Lumen and Laravel.

Getting Started
---

Installation :

```
$ composer require hsbmaulana/menvel-notification
```

How to use it :

- Publish files.

```
$ php artisan vendor:publish --provider="Menvel\Notification\NotificationServiceProvider"
```

```
$ php artisan migrate
```

- Put `Menvel\Notification\NotificationServiceProvider` to service provider configuration list.

Author
---

- Hasby Maulana ([@hsbmaulana](https://linkedin.com/in/hsbmaulana))
