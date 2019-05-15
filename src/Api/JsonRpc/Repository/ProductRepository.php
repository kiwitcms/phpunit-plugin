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

    public function create(Product $model): Product
    {
        $modelData = $this->exportModel($model);

        /** @var Response $response */
        $response = $this->client->send($this->client->request(123, 'Product.create', [(object) $modelData]));

        $result = $response->getRpcResult();

        return $this->hydrateModel($result);
    }

    public function findById(int $productId): ?Product
    {
        $response = $this->client->send($this->client->request(123, 'Product.filter', [(object)['id' => $productId]]));
        /** @var Response $response */
        $result = $response->getRpcResult();
        if (empty($result)) {
            return null;
        }

        return $this->hydrateModel($result[0]);
    }
}
