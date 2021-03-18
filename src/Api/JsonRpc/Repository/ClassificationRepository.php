<?php

namespace KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Repository;

use Graze\GuzzleHttp\JsonRpc\Message\Response;
use KiwiTcmsPhpUnitPlugin\Api\Model\Classification;

class ClassificationRepository extends BaseRepository
{
    protected function getModelClass(): string
    {
        return Classification::class;
    }

    public function __construct(\Graze\GuzzleHttp\JsonRpc\Client $client)
    {
        parent::__construct($client, [
            'id' => 'id',
            'name' => 'name',
        ]);
    }

    public function findFirst(): ?Classification
    {

        // Empty stdClass is transferred as list in python kiwi tcms api
        // there the filter method expects dict so we use 'id_gt' as workaround
        $response = $this->client->send(
            $this->client->request(123, 'Classification.filter', [['id__gt' => 0]])
        );

        $result = $response->getRpcResult();
        if (!isset($result[0])) {
            return null;
        }


        return $this->hydrateModel($result[0]);
    }
}
