<?php

namespace KiwiTcmsPhpUnitPlugin\Api\Model;

use KiwiTcmsPhpUnitPlugin\Api\Model\BaseModel;

class Product extends BaseModel
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
    private $classificationId;

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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $value)
    {
        $this->description = $value;
    }

    public function getClassificationId(): ?int
    {
        return $this->classificationId;
    }

    public function setClassificationId(int $value)
    {
        $this->classificationId = $value;
    }
}
