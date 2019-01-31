<?php

namespace KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Repository;

use KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Repository\BaseRepository;
use Graze\GuzzleHttp\JsonRpc\Message\Response;
use KiwiTcmsPhpUnitPlugin\Api\Model\TestRun;

class TestRunRepository extends BaseRepository
{
    protected function getModelClass(): string
    {
        return TestRun::class;
    }

    public function __construct(\Graze\GuzzleHttp\JsonRpc\Client $client)
    {
        parent::__construct($client, [
            'buildId' => 'build',
            'planId' => 'plan',
            'managerId' => 'manager',
        ]);
    }

    public function findById(int $testRunId): ?TestRun
    {
        $response = $this->client->send($this->client->request(123, 'TestRun.filter', [(object)[
            'run_id' => $testRunId,
        ]]));

        /** @var Response $response */
        $result = $response->getRpcResult();
        if (empty($result)) {
            return null;
        }

        return $this->hydrateModel($result[0]);
    }

    public function create(TestRun $model): TestRun
    {
        $modelData = $this->exportModel($model);

        /** @var Response $response */
        $response = $this->client->send($this->client->request(123, 'TestRun.create', [(object) $modelData]));

        $result = $response->getRpcResult();

        $newModel = $this->hydrateModel($result);

        return $newModel;
    }
}
