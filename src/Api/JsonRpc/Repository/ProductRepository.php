<?php

namespace KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Repository;

use KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Repository\BaseRepository;
use Graze\GuzzleHttp\JsonRpc\Message\Response;
use KiwiTcmsPhpUnitPlugin\Api\Model\Product;

class ProductRepository extends BaseRepository
{
    protected function getModelClass(): string
    {
        return Product::class;
    }

    public function findByName(string $name): ?Product
    {
        $response = $this->client->send($this->client->request(123, 'Product.filter', [(object)['name' => $name]]));
        /** @var Response $response */
        $result = $response->getRpcResult();
        if (empty($result)) {
            return null;
        }

        return $this->hydrateModel($result[0]);
    }
}
