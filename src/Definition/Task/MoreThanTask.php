<?php

namespace LittleCubicleGames\Quests\Definition\Task;

class MoreThanTask implements TaskInterface
{
    const TASK_NAME = 'more-than';

    /** @var mixed */
    private $id;

    /** @var int */
    private $value;

    public function __construct($id, int $value)
    {
        $this->id = $id;
        $this->value = $value;
    }

    public function isFinished(array $progressMap): bool
    {
        $progress = $progressMap[$this->id] ?? 0;

        return $this->value < $progress;
    }
}
