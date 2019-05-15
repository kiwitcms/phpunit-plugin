<?php

namespace KiwiTcmsPhpUnitPlugin\Api\Model;

use KiwiTcmsPhpUnitPlugin\Api\Model\BaseModel;

class TestExecution extends BaseModel
{
    /**
     * @var int
     */
    private $caseRunId;

    /**
     * @var int
     */
    private $runId;

    /**
     * @var int
     */
    private $caseId;

    /**
     * @var int
     */
    private $buildId;

    /**
     * @var int
     */
    private $statusId;

    public function getCaseRunId(): int
    {
        return $this->caseRunId;
    }

    public function setCaseRunId(int $value)
    {
        $this->caseRunId = $value;
    }

    public function getRunId(): ?int
    {
        return $this->runId;
    }

    public function setRunId(int $value)
    {
        $this->runId = $value;
    }

    public function getCaseId(): ?int
    {
        return $this->caseId;
    }

    public function setCaseId(int $value)
    {
        $this->caseId = $value;
    }

    public function getBuildCaseId(): ?int
    {
        return $this->buildId;
    }

    public function setBuildId(int $value)
    {
        $this->buildId = $value;
    }

    public function getStatusId(): ?int
    {
        return $this->statusId;
    }

    public function setStatusId(int $value)
    {
        $this->statusId = $value;
    }
}
