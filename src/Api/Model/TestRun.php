<?php

namespace KiwiTcmsPhpUnitPlugin\Api\Model;

use KiwiTcmsPhpUnitPlugin\Api\Model\BaseModel;
use DateTime;

class TestRun extends BaseModel
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $productVersionId;

    /**
     * @var DateTime
     */
    private $startDate;

    /**
     * @var DateTime
     */
    private $stopDate;

    /**
     * @var string
     */
    private $summary;

    /**
     * @var string
     */
    private $notes;

    /**
     * @var int
     */
    private $plan;

    /**
     * @var int
     */
    private $build;

    /**
     * @var int
     */
    private $managerId;

    /**
     * @var int
     */
    private $defaultTesterId;

    /**
     * @var int[]
     */
    private $tags;

    public function __construct()
    {
        $this->notes = "";
        $this->tags = [];
    }

    public function getRunId(): int
    {
        return $this->id;
    }

    public function setRunId(int $value)
    {
        $this->runId = $value;
    }

    public function getProductVersionId(): ?int
    {
        return $this->productVersionId;
    }

    public function setProductVersionId(int $value)
    {
        $this->productVersionId = $value;
    }

    public function getStartDate(): ?DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(DateTime $value)
    {
        $this->startDate = $value;
    }

    public function getStopDate(): ?DateTime
    {
        return $this->stopDate;
    }

    public function setStopDate(DateTime $value)
    {
        $this->stopDate = $value;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(string $value)
    {
        $this->summary = $value;
    }

    public function getNotes(): ?string
    {
        return $this->notes;
    }

    public function setNotes(string $value)
    {
        $this->notes = $value;
    }

    public function getPlanId(): int
    {
        return $this->plan;
    }

    public function setPlanId(int $value)
    {
        $this->plan = $value;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function setTags(array $value)
    {
        $this->tags = $value;
    }

    public function getBuildId(): int
    {
        return $this->build;
    }

    public function setBuildId(int $value)
    {
        $this->build = $value;
    }

    public function getManagerId(): ?int
    {
        return $this->managerId;
    }

    public function setManagerId(int $value)
    {
        $this->managerId = $value;
    }

    public function getDefaultTesterId(): ?int
    {
        return $this->defaultTesterId;
    }

    public function setDefaultTesterId(int $value)
    {
        $this->defaultTesterId = $value;
    }
}
