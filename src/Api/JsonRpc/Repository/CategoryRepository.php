<?php

namespace KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Repository;

use KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Repository\BaseRepository;
use Graze\GuzzleHttp\JsonRpc\Message\Response;
use KiwiTcmsPhpUnitPlugin\Api\Model\Category;

class CategoryRepository extends BaseRepository
{
    protected function getModelClass(): string
    {
        return Category::class;
    }

    public function __construct(\Graze\GuzzleHttp\JsonRpc\Client $client)
    {
        parent::__construct($client, [
            'productId' => 'product',
        ]);
    }

    public function findFirstByProductId(int $productId): ?Category
    {
        $response = $this->client->send($this->client->request(123, 'Category.filter', [(object)[
            'product_id' => $productId,
        ]]));

        /** @var Response $response */
        $result = $response->getRpcResult();
        if (empty($result)) {
            return null;
        }

        return $this->hydrateModel($result[0]);
    }
}
