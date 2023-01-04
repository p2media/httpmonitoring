<?php

namespace P2media\Httpmonitoring\Domain\Model\Dto;

class Filter
{
    public function __construct(protected string $status = '', protected string $onlyErrors = '', protected string $timeStart = '', protected string $timeEnd = '')
    {
    }

    public function getStatus(): string
    {
        return $this->status;
    }

    public function setStatus(string $status): void
    {
        $this->status = $status;
    }

    public function getOnlyErrors(): string
    {
        return $this->onlyErrors;
    }

    public function setOnlyErrors(string $onlyErrors): void
    {
        $this->onlyErrors = $onlyErrors;
    }

    public function getTimeStart(): string
    {
        return $this->timeStart;
    }

    public function setTimeStart(string $timeStart): void
    {
        $this->timeStart = $timeStart;
    }

    public function getTimeEnd(): string
    {
        return $this->timeEnd;
    }

    public function setTimeEnd(string $timeEnd): void
    {
        $this->timeEnd = $timeEnd;
    }

    /**
     * Convert object to array
     *
     * @return array{status: string, onlyErrors: string, timeStart: string, timeEnd: string}
     */
    public function toArray(): array
    {
        return ['status' => $this->status, 'onlyErrors' => $this->onlyErrors, 'timeStart' => $this->timeStart, 'timeEnd' => $this->timeEnd];
    }
}
