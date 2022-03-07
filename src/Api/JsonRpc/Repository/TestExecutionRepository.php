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

    public function create(TestExecution $model): array
    {
        $modelData = $this->exportModel($model);

        /** @var Response $response */
        $response = $this->client->send($this->client->request(123, 'TestRun.add_case', [
            'case_id' => $modelData['case'],
            'run_id' => $modelData['run']
        ]));

        $result = $response->getRpcResult();
        $testExecutions = [];

        foreach ($result as $testExecution) {
            $testExecutions[] = $this->hydrateModel($testExecution);
        }

        return $testExecutions;
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
