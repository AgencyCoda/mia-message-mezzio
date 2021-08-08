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
    $app->route('/mia-message/write', [\Mia\Auth\Handler\AuthHandler::class, \Mia\Message\Handler\WriteHandler::class], ['GET', 'POST', 'OPTIONS', 'HEAD'], 'mia-message.
    write');
    $app->route('/mia-message/write-and-create-channel', [\Mia\Auth\Handler\AuthHandler::class, \Mia\Message\Handler\WriteAndCreateChannelHandler::class], ['GET', 'POST', 'OPTIONS', 'HEAD'], 'mia-message.write-and-create-channel');
    $app->route('/mia-message/channels', [\Mia\Auth\Handler\AuthHandler::class, \Mia\Message\Handler\ChannelsHandler::class], ['GET', 'POST', 'OPTIONS', 'HEAD'], 'mia-message.channels');
    $app->route('/mia-message/messages', [\Mia\Auth\Handler\AuthHandler::class, \Mia\Message\Handler\MessagesHandler::class], ['GET', 'POST', 'OPTIONS', 'HEAD'], 'mia-message.messages');
    $app->route('/mia-message/messages-group-date', [\Mia\Auth\Handler\AuthHandler::class, \Mia\Message\Handler\MessagesGroupDateHandler::class], ['GET', 'POST', 'OPTIONS', 'HEAD'], 'mia-message.messages-group-date');
    $app->route('/mia-message/create-direct-channel', [\Mia\Auth\Handler\AuthHandler::class, \Mia\Message\Handler\CreateDirectChannelHandler::class], ['GET', 'POST', 'OPTIONS', 'HEAD'], 'mia-message.create-direct-channel');
    $app->route('/mia-message/fetch-direct-channel/{user_id}', [\Mia\Auth\Handler\AuthHandler::class, \Mia\Message\Handler\FetchDirectChannelHandler::class], ['GET', 'OPTIONS', 'HEAD'], 'mia-message.fetch-direct-channel');
    $app->route('/mia-message/new-messages', [\Mia\Auth\Handler\AuthHandler::class, \Mia\Message\Handler\NewMessagesHandler::class], ['GET', 'OPTIONS', 'HEAD'], 'mia-message.new-messages');
```