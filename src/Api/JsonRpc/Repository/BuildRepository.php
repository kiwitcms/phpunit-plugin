<?php

namespace KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Repository;

use KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Repository\BaseRepository;
use Graze\GuzzleHttp\JsonRpc\Message\Response;
use KiwiTcmsPhpUnitPlugin\Api\Model\Build;

class BuildRepository extends BaseRepository
{
    protected function getModelClass(): string
    {
        return Build::class;
    }

    public function __construct(\Graze\GuzzleHttp\JsonRpc\Client $client)
    {
        parent::__construct($client, [
            'productId' => 'product',
        ]);
    }

    public function findByProductIdAndBuild(int $productId, string $build): ?Build
    {
        $response = $this->client->send($this->client->request(123, 'Build.filter', [(object)[
            'name' => $build,
            'product' => $productId,
        ]]));

        /** @var Response $response */
        $result = $response->getRpcResult();
        if (empty($result)) {
            return null;
        }

        return $this->hydrateModel($result[0]);
    }

    public function create(Build $model): Build
    {
        $modelData = $this->exportModel($model);

        /** @var Response $response */
        $response = $this->client->send($this->client->request(123, 'Build.create', [(object) $modelData]));

        $result = $response->getRpcResult();

        $newModel = $this->hydrateModel($result);

        return $newModel;
    }
}
