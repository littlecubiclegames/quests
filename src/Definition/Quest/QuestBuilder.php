<?php declare(strict_types=1);

namespace LittleCubicleGames\Quests\Definition\Quest;

use LittleCubicleGames\Quests\Definition\Task\TaskBuilder;

class QuestBuilder
{
    /** @var TaskBuilder */
    private $taskBuilder;

    public function __construct(TaskBuilder $taskBuilder)
    {
        $this->taskBuilder = $taskBuilder;
    }

    public function build(array $data): Quest
    {
        $task = $this->taskBuilder->build($data['task']);

        return new Quest($data['id'], $task);
    }
}
