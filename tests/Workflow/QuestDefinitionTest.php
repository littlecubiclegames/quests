<?php declare(strict_types=1);

namespace LittleCubicleGames\Tests\Quests\Workflow;

use LittleCubicleGames\Quests\Workflow\QuestDefinition;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Workflow\Definition;

class QuestDefinitionTest extends TestCase
{
    public function testBuild()
    {
        $definition = new QuestDefinition();

        $this->assertInstanceOf(Definition::class, $definition->build());
    }
}
