<?php

namespace LittleCubicleGames\Tests\Quests\Definition\Task;

use LittleCubicleGames\Quests\Definition\Task\AndTask;
use LittleCubicleGames\Quests\Definition\Task\EqualToTask;
use LittleCubicleGames\Quests\Definition\Task\LessThanTask;
use LittleCubicleGames\Quests\Definition\Task\MoreThanTask;
use LittleCubicleGames\Quests\Definition\Task\OrTask;
use LittleCubicleGames\Quests\Definition\Task\TaskBuilder;
use PHPUnit\Framework\TestCase;

class TaskBuilderTest extends TestCase
{
    /** @var TaskBuilder */
    private $builder;

    protected function setUp()
    {
        $this->builder = new TaskBuilder();
    }

    /**
     * @dataProvider buildProvider
     */
    public function testBuild(array $taskData, $expectedClass)
    {
        $task = $this->builder->build($taskData);

        $this->assertInstanceOf($expectedClass, $task);
    }

    public function buildProvider()
    {
        return [
            [['id' => 1, 'value' => 1, 'type' => EqualToTask::TASK_NAME], EqualToTask::class],
            [['id' => 1, 'value' => 1, 'type' => LessThanTask::TASK_NAME], LessThanTask::class],
            [['id' => 1, 'value' => 1, 'type' => MoreThanTask::TASK_NAME], MoreThanTask::class],
            [['type' => OrTask::TASK_NAME, 'children' => [['id' => 1, 'value' => 1, 'type' => EqualToTask::TASK_NAME],['id' => 1, 'value' => 1, 'type' => EqualToTask::TASK_NAME]]], OrTask::class],
            [['type' => AndTask::TASK_NAME, 'children' => [['id' => 1, 'value' => 1, 'type' => EqualToTask::TASK_NAME],['id' => 1, 'value' => 1, 'type' => EqualToTask::TASK_NAME]]], AndTask::class],
        ];
    }
}
