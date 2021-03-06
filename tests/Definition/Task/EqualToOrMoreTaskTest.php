<?php declare(strict_types=1);

namespace LittleCubicleGames\Tests\Quests\Definition\Task;

use LittleCubicleGames\Quests\Definition\Task\EqualToOrMoreTask;
use PHPUnit\Framework\TestCase;

class EqualToOrMoreTaskTest extends TestCase
{
    /**
     * @dataProvider isFinishedProvider
     */
    public function testIsFinished(int $value, int $taskId, array $progressMap, bool $expected): void
    {
        $task = new EqualToOrMoreTask($taskId, 'type', $value);
        $this->assertSame($expected, $task->isFinished($progressMap));
    }

    public function isFinishedProvider(): array
    {
        return [
            [0, 0, [0 => 0], true],
            [1, 0, [0 => 0], false],
            [0, 0, [0 => 1], true],
            [0, 0, [0 => -1], false],
            [0, 1, [0 => 0, 1 => 1], true],
        ];
    }

    public function testGetTaskIdTypes(): void
    {
        $task = new EqualToOrMoreTask(1, 'type', 10);
        $this->assertEquals([1 => 'type'], $task->getTaskIdTypes());
    }

    public function testGetTaskIdAttributes(): void
    {
        $task = new EqualToOrMoreTask(1, 'type', 10, ['subype' => 'subtype']);
        $this->assertEquals([1 => ['subype' => 'subtype']], $task->getTaskIdAttributes());
    }
}
