<?php declare(strict_types=1);

namespace LittleCubicleGames\Tests\Quests\Workflow;

use LittleCubicleGames\Quests\Workflow\QuestDefinition;
use PHPUnit\Framework\TestCase;

class QuestDefinitionTest extends TestCase
{
    public function testBuild(): void
    {
        $questDefinition = new QuestDefinition();

        $definition = $questDefinition->build();

        $this->assertSame(QuestDefinition::STATE_AVAILABLE, $definition->getInitialPlace());
        $this->assertSame(QuestDefinition::STATES, array_values($definition->getPlaces()));
    }
}
