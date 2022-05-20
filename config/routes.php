<?php

declare(strict_types=1);

use Mezzio\Application;
use Mezzio\MiddlewareFactory;
use Psr\Container\ContainerInterface;

return static function (Application $app, MiddlewareFactory $factory, ContainerInterface $container): void {
    $app->route('/produto', App\Handler\ProdutoHandler::class, ['GET', 'POST', 'PATCH', 'DELETE'], 'produto');
};

