<?php

namespace LittleCubicleGames\Quests\Definition\Task;

abstract class AbstractTask implements TaskInterface
{
    /** @var mixed */
    protected $id;

    /** @var string */
    protected $type;

    /** @var int */
    protected $value;

    public function __construct($id, string $type, int $value)
    {
        $this->id = $id;
        $this->type = $type;
        $this->value = $value;
    }

    public function getTaskIdTypes(): array
    {
        return [$this->id => $this->type];
    }
}
