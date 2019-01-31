<?php

namespace KiwiTcmsPhpUnitPlugin\Api\Model;

use KiwiTcmsPhpUnitPlugin\Api\Model\BaseModel;

class ProductVersion extends BaseModel
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $value;

    /**
     * @var int
     */
    private $productId;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $value)
    {
        $this->id = $value;
    }

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value)
    {
        $this->value = $value;
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
