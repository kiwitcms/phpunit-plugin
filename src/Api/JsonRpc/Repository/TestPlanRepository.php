<?php

namespace KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Repository;

use KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Repository\BaseRepository;
use Graze\GuzzleHttp\JsonRpc\Message\Response;
use KiwiTcmsPhpUnitPlugin\Api\Model\TestPlan;

class TestPlanRepository extends BaseRepository
{
    protected function getModelClass(): string
    {
        return TestPlan::class;
    }

    public function __construct(\Graze\GuzzleHttp\JsonRpc\Client $client)
    {
        parent::__construct($client, [
            'productId' => 'product',
            'productVersionId' => 'product_version',
            'typeId' => 'type',
        ]);
    }

    protected function exportModel($model): array
    {
        $modelData = parent::exportModel($model);
        $modelData['is_active'] = (int) $modelData['is_active'];
        return $modelData;
    }

    public function create(TestPlan $model): TestPlan
    {
        $modelData = $this->exportModel($model);

        /** @var Response $response */
        $response = $this->client->send($this->client->request(123, 'TestPlan.create', [(object) $modelData]));

        $result = $response->getRpcResult();

        $newModel = $this->hydrateModel($result);

        return $newModel;
    }

    public function addTestCase(int $testPlanId, int $testCaseId)
    {
        $this->client->send($this->client->request(123, 'TestPlan.add_case', [
            'case_id' => $testCaseId,
            'plan_id' => $testPlanId,
        ]));
    }

    public function findById(int $testPlanId)
    {
        $response = $this->client->send($this->client->request(123, 'TestPlan.filter', [(object)[
            'plan_id'   =>    $testPlanId,
        ]]));

        /** @var Response $response */
        $result = $response->getRpcResult();
        if (empty($result)) {
            return null;
        }

        return $this->hydrateModel($result[0]);
    }
}
