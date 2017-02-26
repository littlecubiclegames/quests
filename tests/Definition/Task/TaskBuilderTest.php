<?php declare(strict_types=1);

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
            [['id' => 1, 'value' => 1, 'operator' => EqualToTask::TASK_NAME, 'type' => 'type'], EqualToTask::class],
            [['id' => 1, 'value' => 1, 'operator' => LessThanTask::TASK_NAME, 'type' => 'type'], LessThanTask::class],
            [['id' => 1, 'value' => 1, 'operator' => MoreThanTask::TASK_NAME, 'type' => 'type'], MoreThanTask::class],
            [
                ['operator' => OrTask::TASK_NAME, 'children' => [
                    ['id' => 1, 'value' => 1, 'operator' => EqualToTask::TASK_NAME, 'type' => 'type'],
                    ['id' => 1, 'value' => 1, 'operator' => EqualToTask::TASK_NAME, 'type' => 'type'],
                ]], OrTask::class,
            ],
            [
                ['operator' => AndTask::TASK_NAME, 'children' => [
                    ['id' => 1, 'value' => 1, 'operator' => EqualToTask::TASK_NAME, 'type' => 'type'],
                    ['id' => 1, 'value' => 1, 'operator' => EqualToTask::TASK_NAME, 'type' => 'type'],
                ]], AndTask::class,
            ],
        ];
    }
}
