# AgencyCoda Message Mezzio

1. Incluir libreria:
```bash
composer require agencycoda/mia-core-mezzio
composer require agencycoda/mia-auth-mezzio
composer require agencycoda/mia-message-mezzio
```
5. Agregando las rutas:
```php
    /** MIA Message **/
    $app->route('/mia-message/write', [\Mia\Auth\Handler\AuthHandler::class, \Mia\Message\Handler\WriteHandler::class], ['GET', 'POST', 'OPTIONS', 'HEAD'], 'mia-message.write');
    $app->route('/mia-message/channels', [\Mia\Auth\Handler\AuthHandler::class, \Mia\Message\Handler\ChannelsHandler::class], ['GET', 'POST', 'OPTIONS', 'HEAD'], 'mia-message.channels');
```