<?php

namespace KiwiTcmsPhpUnitPlugin\Api\Model;

use KiwiTcmsPhpUnitPlugin\Api\Model\BaseModel;
use DateTime;

class TestPlan extends BaseModel
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
    private $text;

    /**
     * @var DateTime
     */
    private $createDate;

    /**
     * @var bool
     */
    private $isActive;

    /**
     * @var string
     */
    private $extraLink;

    /**
     * @var int
     */
    private $productVersionId;

    /**
     * @var int
     */
    private $authorId;

    /**
     * @var int
     */
    private $productId;

    /**
     * @var int
     */
    private $typeId;

    /**
     * @var int
     */
    private $parentId;

    /**
     * @var int[]
     */
    private $tags;

    public function __construct()
    {
        $this->createDate = new DateTime();
        $this->isActive = true;
        $this->text = "";
        $this->tags = [];
    }

    public function getPlanId(): int
    {
        return $this->id;
    }

    public function setPlanId(int $value)
    {
        $this->planId = $value;
    }

    public function getCreateDate(): ?DateTime
    {
        return $this->createDate;
    }

    public function setCreateDate(DateTime $value)
    {
        $this->createDate = $value;
    }

    public function getIsActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $value)
    {
        $this->isActive = $value;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $value)
    {
        $this->name = $value;
    }

    public function getExtraLink(): ?string
    {
        return $this->extraLink;
    }

    public function setExtraLink(string $value)
    {
        $this->extraLink = $value;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $value)
    {
        $this->text = $value;
    }

    public function getAuthorId(): ?int
    {
        return $this->authorId;
    }

    public function setAuthorId(int $value)
    {
        $this->authorId = $value;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function setTags(array $value)
    {
        $this->tags = $value;
    }

    public function getProductId(): ?int
    {
        return $this->productId;
    }

    public function setProductId(int $value)
    {
        $this->productId = $value;
    }

    public function getProductVersionId(): ?int
    {
        return $this->productVersionId;
    }

    public function setProductVersionId(int $value)
    {
        $this->productVersionId = $value;
    }

    public function getTypeId(): ?int
    {
        return $this->typeId;
    }

    public function setTypeId(int $value)
    {
        $this->typeId = $value;
    }

    public function getParentId(): ?int
    {
        return $this->parentId;
    }

    public function setParentId(int $value)
    {
        $this->parentId = $value;
    }
}
