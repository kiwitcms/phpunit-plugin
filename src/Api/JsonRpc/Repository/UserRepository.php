<?php

namespace KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Repository;

use KiwiTcmsPhpUnitPlugin\Api\JsonRpc\Repository\BaseRepository;
use Graze\GuzzleHttp\JsonRpc\Message\Response;
use KiwiTcmsPhpUnitPlugin\Api\Model\User;

class UserRepository extends BaseRepository
{
    protected function getModelClass(): string
    {
        return User::class;
    }

    public function findByUsername(string $username): ?User
    {
        $response = $this->client->send($this->client->request(123, 'User.filter', [(object)[
            'username' => $username
        ]]));

        /** @var Response $response */
        $result = $response->getRpcResult();
        if (empty($result)) {
            return null;
        }

        return $this->hydrateModel($result[0]);
    }
}
