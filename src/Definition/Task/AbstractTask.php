<?php declare(strict_types=1);

namespace LittleCubicleGames\Quests\Definition\Task;

abstract class AbstractTask implements TaskInterface
{
    /** @var int */
    protected $id;
    /** @var string */
    protected $type;
    /** @var int */
    protected $value;
    /** @var mixed[] */
    private $attributes;

    public function __construct(int $id, string $type, int $value, array $attributes = [])
    {
        $this->id = $id;
        $this->type = $type;
        $this->value = $value;
        $this->attributes = $attributes;
    }

    public function getTaskIdTypes(): array
    {
        return [$this->id => $this->type];
    }

    public function getTaskIdAttributes(): array
    {
        return [$this->id => $this->attributes];
    }
}
