<?php

namespace KiwiTcmsPhpUnitPlugin\Api\Model;

use KiwiTcmsPhpUnitPlugin\Api\Model\BaseModel;

class TestExecution extends BaseModel
{

	/**
	 * @var int
	 */
	private $id;

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

	/**
	 * @return int
	 */
	public function getId(): int
	{
		return $this->id;
	}

	/**
	 * @param int $id
	 */
	public function setId(int $id): void
	{
		$this->id = $id;
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
