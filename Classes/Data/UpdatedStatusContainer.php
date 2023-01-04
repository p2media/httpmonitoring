<?php

namespace P2media\Httpmonitoring\Data;

class UpdatedStatusContainer
{
    /**
     * @var array<UpdatedStatus>
     */
    private array $errorArray = [];

    /**
     * @var array<UpdatedStatus>
     */
    private array $improvedStatusArray = [];

    /**
     * @return array<UpdatedStatus>
     */
    public function getErrorArray(): array
    {
        return $this->errorArray;
    }

    public function addError(UpdatedStatus $updatedStatus): void
    {
        $this->errorArray[] = $updatedStatus;
    }

    /**
     * @return array<UpdatedStatus>
     */
    public function getImprovedStatusArray(): array
    {
        return $this->improvedStatusArray;
    }

    public function addImprovedStatus(UpdatedStatus $updatedStatus): void
    {
        $this->improvedStatusArray[] = $updatedStatus;
    }
}
