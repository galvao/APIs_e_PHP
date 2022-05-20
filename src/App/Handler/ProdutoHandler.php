<?php

declare(strict_types=1);

namespace App\Handler;

use Laminas\Db\Adapter\Adapter;
use Laminas\Db\Sql\Sql;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\Response\EmptyResponse;
use Mezzio\Router;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ProdutoHandler implements RequestHandlerInterface
{
    private $containerName;
    private $router;
    private $adapter;

    public function __construct(string $containerName, Router\RouterInterface $router, $adapter)
    {
        $this->containerName = $containerName;
        $this->router        = $router;
        $this->adapter = $adapter;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        $method = $request->getMethod();
        $id ??= $request->getHeader('x-id');
        $body = $request->getParsedBody();
        $sql = new Sql($this->adapter);

        switch ($method) {
            case 'GET':
                $qry = $sql->select('produto');
                $qry->where(['id' => $id]);

                $stmt = $sql->prepareStatementForSqlObject($qry);
                $recordSet = $stmt->execute();

                return new JsonResponse(
                    [
                        'data' => $recordSet->current()
                    ],
                    200
                );
            break;

            case 'POST':
                $qry = $sql->insert('produto');
                $qry->columns(['nome', 'preco']);
                $qry->values([$body['nome'], $body['preco']]);

                $stmt = $sql->prepareStatementForSqlObject($qry);
                $stmt->execute();

                return new JsonResponse(
                    [
                        'data' => $body
                    ],
                    201
                );
            break;

            case 'PATCH':
                $qry = $sql->update('produto');
                $qry->set(['nome' => $body['nome'], 'preco' => $body['preco']]);
                $qry->where(['id' => $id]);

                $stmt = $sql->prepareStatementForSqlObject($qry);
                $stmt->execute();

                return new EmptyResponse(204);
            break;

            case 'DELETE':
                $qry = $sql->delete('produto');
                $qry->where(['id' => $id]);

                $stmt = $sql->prepareStatementForSqlObject($qry);
                $stmt->execute();
                return new EmptyResponse(204);
            break;
        }
    }
}
