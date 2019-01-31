<?php

namespace KiwiTcmsPhpUnitPlugin\Api\Model;

use KiwiTcmsPhpUnitPlugin\Api\Model\BaseModel;

class Category extends BaseModel
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
     * @var int
     */
    private $productId;

    /**
     * @var string
     */
    private $description;

    public function __construct()
    {
        $this->description = "";
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $value)
    {
        $this->id = $value;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $value)
    {
        $this->name = $value;
    }

    public function getProductId(): ?int
    {
        return $this->productId;
    }

    public function setProductId(int $value)
    {
        $this->productId = $value;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $value)
    {
        $this->description = $value;
    }
}
