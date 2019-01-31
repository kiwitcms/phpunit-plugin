<?php

namespace KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Repository;

use KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Repository\BaseRepository;
use Graze\GuzzleHttp\JsonRpc\Message\Response;
use KiwiTcmsPhpUnitPlugin\Api\Model\TestCase;

class TestCaseRepository extends BaseRepository
{
    protected function getModelClass(): string
    {
        return TestCase::class;
    }

    public function __construct(\Graze\GuzzleHttp\JsonRpc\Client $client)
    {
        parent::__construct($client, [
            'productId' => 'product',
            'categoryId' => 'category',
            'priorityId' => 'priority'
        ]);
    }

    public function findByTestRunId(int $testRunId): ?array
    {
        $testCasesArr = [];

        $response = $this->client->send($this->client->request(123, 'TestRun.get_cases', [$testRunId,
        ]));

        /** @var Response $response */
        $result = $response->getRpcResult();

        foreach ($result as $testCase) {
            $testCasesArr[md5($testCase['summary'])] = $this->hydrateModel($testCase);
        }

        return $testCasesArr;
    }

    public function create(TestCase $model): TestCase
    {
        $modelData = $this->exportModel($model);

        /** @var Response $response */
        $response = $this->client->send($this->client->request(123, 'TestCase.create', [(object) $modelData]));

        $result = $response->getRpcResult();

        $newModel = $this->hydrateModel($result);

        return $newModel;
    }


    protected function exportModel($model): array
    {
        $modelData = parent::exportModel($model);
        $modelData['is_automated'] = (int) $modelData['is_automated'];
        return $modelData;
    }

    public function createModel(TestCase $model)
    {
        $modelData = $this->exportModel($model);

        /** @var Response $response */
        $response = $this->client->send($this->client->request(123, 'TestCase.create', [(object) $modelData]));

        $result = $response->getRpcResult();

        $newModel = $this->hydrateModel($result);

        return $newModel;
    }
}
