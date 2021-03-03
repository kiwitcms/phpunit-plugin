<?php

namespace KiwiTcmsPhpUnitPlugin\Api\Model;

use KiwiTcmsPhpUnitPlugin\Api\Model\BaseModel;

class Build extends BaseModel
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var int
     */
    private $version;

    public function __construct()
    {
        $this->description = "";
    }

    public function getBuildId(): int
    {
        return $this->id;
    }

    public function setBuildId(int $value)
    {
        $this->buildId = $value;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $value)
    {
        $this->name = $value;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $value)
    {
        $this->description = $value;
    }

    public function getProductId(): ?int
    {
        return $this->productId;
    }

    public function setProductId(int $value)
    {
        $this->productId = $value;
    }
}
