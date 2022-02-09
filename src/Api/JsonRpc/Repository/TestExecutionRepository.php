<?php

namespace KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Repository;

use KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Repository\BaseRepository;
use Graze\GuzzleHttp\JsonRpc\Message\Response;
use KiwiTcmsPhpUnitPlugin\Api\Model\TestExecution;

class TestExecutionRepository extends BaseRepository
{
    protected function getModelClass(): string
    {
        return TestExecution::class;
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

    public function findByTestCaseIdAndTestRunId(int $testCaseId, int $testRunId): ?TestExecution
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

    public function updateStatus(int $testExecutionId, int $statusId)
    {
        /** @var Response $response */
        $response =  $this->client->send($this->client->request(123, 'TestExecution.update', [
            'execution_id' => $testExecutionId,
            'values' => [
                'status' => $statusId
            ]
        ]));

        $result = $response->getRpcResult();

        return $this->hydrateModel($result);
    }

    public function create(TestExecution $model): TestExecution
    {
        $modelData = $this->exportModel($model);

        /** @var Response $response */
        $response = $this->client->send($this->client->request(123, 'TestRun.add_case', [
            'case_id' => $modelData['case'],
            'run_id' => $modelData['run']
        ]));

        $result = $response->getRpcResult()[0];

        $newModel = $this->hydrateModel($result);

        return $newModel;
    }

    public function addComment(int $testExecutionId, string $comment)
    {
        /** @var Response $response */
        $this->client->send($this->client->request(123, 'TestExecution.add_comment', [
            'execution_id' => $testExecutionId,
            'comment' => $comment
        ]));
    }
}
