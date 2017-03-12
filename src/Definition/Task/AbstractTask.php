<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
namespace LittleCubicleGames\Quests\Definition\Task;

abstract class AbstractTask implements TaskInterface
{
    /** @var mixed */
    protected $id;
    /** @var string */
    protected $type;
    /** @var int */
    protected $value;
    public function __construct($id, $type, $value)
    {
        $this->id = $id;
        $this->type = $type;
        $this->value = $value;
    }
    public function getTaskIdTypes()
    {
        return [$this->id => $this->type];
    }
}
