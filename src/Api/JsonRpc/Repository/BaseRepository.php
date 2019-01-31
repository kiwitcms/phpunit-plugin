<?php

namespace KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Repository;

use Graze\GuzzleHttp\JsonRpc\Client as GuzzleJsonRpcClient;
use Zend\Hydrator\ReflectionHydrator;
use Zend\Hydrator\NamingStrategy\UnderscoreNamingStrategy;
use Zend\Hydrator\NamingStrategy\CompositeNamingStrategy;
use Zend\Hydrator\NamingStrategy\MapNamingStrategy;

class BaseRepository
{
    protected $client;
    protected $hydrator;

    public function __construct(GuzzleJsonRpcClient $client, $extractionMap = [])
    {
        $this->client = $client;

        $underscoreNamingStrategy = new UnderscoreNamingStrategy();

        $mapStrategy = MapNamingStrategy::createFromExtractionMap($extractionMap);

        $mapStrategyMapping = [];
        foreach ($extractionMap as $key => $value) {
            $mapStrategyMapping[$key] = $mapStrategy;
        }

        $namingStrategy = new CompositeNamingStrategy($mapStrategyMapping, $underscoreNamingStrategy);


        $this->hydrator = new ReflectionHydrator();
        $this->hydrator->setNamingStrategy($namingStrategy);
    }

    protected function getModelClass(): string
    {
        return "";
    }

    protected function hydrateModel(array $data): object
    {
        $modelClass = $this->getModelClass();
        $model = new $modelClass();
        $this->hydrator->hydrate($data, $model);

        return $model;
    }

    protected function exportModel($model): array
    {
        return $this->hydrator->extract($model);
    }
}
