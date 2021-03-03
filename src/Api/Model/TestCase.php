<?php

namespace KiwiTcmsPhpUnitPlugin\Api\Model;

use KiwiTcmsPhpUnitPlugin\Api\Model\BaseModel;
use DateTime;

class TestCase extends BaseModel
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var DateTime
     */
    private $createDate;

    /**
     * @var bool
     */
    private $isAutomated;

    /**
     * @var string
     */
    private $script;

    /**
     * @var string
     */
    private $arguments;

    /**
     * @var string
     */
    private $extraLink;

    /**
     * @var string
     */
    private $summary;

    /**
     * @var string
     */
    private $requirement;

    /**
     * @var string
     */
    private $notes;

    /**
     * @var string
     */
    private $text;

    /**
     * @var int
     */
    private $case_status;

    /**
     * @var int
     */
    private $categoryId;

    /**
     * @var int
     */
    private $priorityId;

    /**
     * @var int
     */
    private $author;

    /**
     * @var int
     */
    private $defaultTesterId;

    /**
     * @var int
     */
    private $reviewerId;

    /**
     * @var int[]
     */
    private $plans;

    /**
     * @var int[]
     */
    private $components;

    /**
     * @var int[]
     */
    private $tags;

    /**
     * @var int
     */
    private $productId;

    public function __construct()
    {
        $this->createDate = new DateTime();
        $this->isAutomated = false;
        $this->plans = [];
        $this->components = [];
        $this->tags = [];
    }

    public function getCaseId(): int
    {
        return $this->id;
    }

    public function setCaseId(int $value)
    {
        $this->id = $value;
    }

    public function getCreateDate(): ?DateTime
    {
        return $this->createDate;
    }

    public function setCreateDate(DateTime $value)
    {
        $this->createDate = $value;
    }

    public function getIsAutomated(): bool
    {
        return $this->isAutomated;
    }

    public function setIsAutomated(bool $value)
    {
        $this->isAutomated = $value;
    }

    public function getScript(): ?string
    {
        return $this->script;
    }

    public function setScript(string $value)
    {
        $this->script = $value;
    }

    public function getArguments(): ?string
    {
        return $this->arguments;
    }

    public function setArguments(string $value)
    {
        $this->arguments = $value;
    }

    public function getExtraLink(): ?string
    {
        return $this->extraLink;
    }

    public function setExtraLink(string $value)
    {
        $this->extraLink = $value;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(string $value)
    {
        $this->summary = $value;
    }

    public function getRequirement(): ?string
    {
        return $this->requirement;
    }

    public function setRequirement(string $value)
    {
        $this->requirement = $value;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(string $value)
    {
        $this->notes = $value;
    }

    public function getText(): ?string
    {
        return $this->text;
    }

    public function setText(string $value)
    {
        $this->text = $value;
    }

    public function getCaseStatusId(): ?int
    {
        return $this->case_status;
    }

    public function setCaseStatusId(int $value)
    {
        $this->case_status = $value;
    }

    public function getCategoryId(): ?int
    {
        return $this->categoryId;
    }

    public function setCategoryId(int $value)
    {
        $this->categoryId = $value;
    }

    public function getPriorityId(): ?int
    {
        return $this->priorityId;
    }

    public function setPriorityId(int $value)
    {
        $this->priorityId = $value;
    }

    public function getAuthorId(): ?int
    {
        return $this->author;
    }

    public function setAuthorId(int $value)
    {
        $this->author = $value;
    }

    public function getDefaultTesterId(): ?int
    {
        return $this->defaultTesterId;
    }

    public function setDefaultTesterId(int $value)
    {
        $this->defaultTesterId = $value;
    }

    public function getReviewerId(): ?int
    {
        return $this->reviewerId;
    }

    public function setReviewerId(int $value)
    {
        $this->reviewerId = $value;
    }

    public function getPlans(): array
    {
        return $this->plans;
    }

    public function setPlans(array $value)
    {
        $this->plans = $value;
    }

    public function getComponents(): array
    {
        return $this->components;
    }

    public function setComponents(array $value)
    {
        $this->components = $value;
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
}
