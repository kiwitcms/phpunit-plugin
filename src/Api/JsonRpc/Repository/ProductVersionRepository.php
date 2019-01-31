<?php

namespace KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Repository;

use KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Repository\BaseRepository;
use Graze\GuzzleHttp\JsonRpc\Message\Response;
use KiwiTcmsPhpUnitPlugin\Api\Model\ProductVersion;

class ProductVersionRepository extends BaseRepository
{
    protected function getModelClass(): string
    {
        return ProductVersion::class;
    }

    public function __construct(\Graze\GuzzleHttp\JsonRpc\Client $client)
    {
        parent::__construct($client, [
            'productId' => 'product',
        ]);
    }

    public function findByProductIdAndVersion(int $productId, string $version): ?ProductVersion
    {
        $response = $this->client->send($this->client->request(123, 'Version.filter', [(object)[
            'product'   =>    $productId,
            'value'     =>    $version,
        ]]));

        /** @var Response $response */
        $result = $response->getRpcResult();
        if (empty($result)) {
            return null;
        }

        return $this->hydrateModel($result[0]);
    }

    public function create(ProductVersion $model): ProductVersion
    {
        $modelData = $this->exportModel($model);

        /** @var Response $response */
        $response = $this->client->send($this->client->request(123, 'Version.create', [(object) $modelData]));

        $result = $response->getRpcResult();

        return $this->hydrateModel($result);
    }
}
