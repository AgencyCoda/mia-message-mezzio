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
    //$app->route('/mia-finder/tree-folders', [\Mia\Auth\Handler\AuthHandler::class, \Mia\Finder\Handler\TreeFoldersHandler::class], ['GET', 'POST', 'OPTIONS', 'HEAD'], 'mia-finder.tree-folders');
    //$app->route('/mia-finder/list-items', [\Mia\Auth\Handler\AuthHandler::class, \Mia\Finder\Handler\ListItemsHandler::class], ['GET', 'POST', 'OPTIONS', 'HEAD'], 'mia-finder.list-items');
    //$app->route('/mia-finder/upload-item', [\Mia\Auth\Handler\AuthHandler::class, \Mia\Finder\Handler\UploadItemHandler::class], ['POST', 'OPTIONS', 'HEAD'], 'mia-finder.upload-item');
```