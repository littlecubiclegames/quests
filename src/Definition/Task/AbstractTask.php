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
    /** @var mixed[] */
    private $attributes;
    public function __construct($id, $type, $value, array $attributes = [])
    {
        $this->id = $id;
        $this->type = $type;
        $this->value = $value;
        $this->attributes = $attributes;
    }
    public function getTaskIdTypes()
    {
        return array($this->id => $this->type);
    }
    public function getTaskIdAttributes()
    {
        return [$this->id => $this->attributes];
    }
}
