<h1 align="center">Menvel-Notification</h1>

Menvel-Notification is a notification helper for Lumen and Laravel.

Getting Started
---

Installation :

```
$ composer require hsbmaulana/menvel-notification
```

How to use it :

- Put `Menvel\Notification\NotificationServiceProvider` to service provider configuration list.

- Migrate.

```
$ php artisan migrate
```

- Sample usage.

```php
use Menvel\Notification\Contracts\Repository\INotificationRepository;

$repository = app(INotificationRepository::class);
// $repository->setUser(...); //
// $repository->getUser(); //

// $repository->markAsRead(...); //
// $repository->clear('...'); //
// $repository->all(); //
```

Author
---

- Hasby Maulana ([@hsbmaulana](https://linkedin.com/in/hsbmaulana))
