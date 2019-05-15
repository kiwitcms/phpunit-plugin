<?php

namespace KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Repository;

use KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Repository\BaseRepository;
use Graze\GuzzleHttp\JsonRpc\Message\Response;
use KiwiTcmsPhpUnitPlugin\Api\Model\TestCaseRun;

class TestCaseRunRepository extends BaseRepository
{
    protected function getModelClass(): string
    {
        return TestCaseRun::class;
    }

    public function __construct(\Graze\GuzzleHttp\JsonRpc\Client $client)
    {
        parent::__construct($client, [
            'runId' => 'run',
            'caseId' => 'case',
            'buildId' => 'build',
            'statusId' => 'status',
        ]);
    }

    public function findByTestCaseIdAndTestRunId(int $testCaseId, int $testRunId): ?TestCaseRun
    {
        $response = $this->client->send($this->client->request(123, 'TestExecution.filter', [(object) [
            'case_id' => $testCaseId,
            'run_id' => $testRunId,
        ]]));

        /** @var Response $response */
        $result = $response->getRpcResult();
        if (empty($result)) {
            return null;
        }

        return $this->hydrateModel($result[0]);
    }

    public function updateStatus(int $testCaseRunId, int $statusId)
    {
        /** @var Response $response */
        $response =  $this->client->send($this->client->request(123, 'TestExecution.update', [
            'case_run_id' => $testCaseRunId,
            'values' => [
                'case_run_status' => $statusId
            ]
        ]));

        $result = $response->getRpcResult();

        return $this->hydrateModel($result);
    }

    public function create(TestCaseRun $model): TestCaseRun
    {
        $modelData = $this->exportModel($model);

        /** @var Response $response */
        $response = $this->client->send($this->client->request(123, 'TestExecution.create', [(object) $modelData]));

        $result = $response->getRpcResult();

        $newModel = $this->hydrateModel($result);

        return $newModel;
    }
}
