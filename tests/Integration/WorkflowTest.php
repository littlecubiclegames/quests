<?php declare(strict_types=1);

namespace LittleCubicleGames\Tests\Quests\Integration;

use LittleCubicleGames\Quests\Workflow\QuestDefinitionInterface;
use LittleCubicleGames\Tests\Quests\Mock\Entity\MockQuest;
use Symfony\Component\Workflow\Event\Event;
use Symfony\Component\Workflow\Marking;
use Symfony\Component\Workflow\Transition;
use Symfony\Component\Workflow\Workflow;

class WorkflowTest extends AbstractIntegrationTest
{
    protected function setUp()
    {
        parent::setUp();

        $quest = $this->app['cubicle.quests.definition.questbuilder']->build([
            'id' => 0,
            'task' => [
                'id' => 1,
                'type' => 'reject-quests',
                'operator' => 'less-than',
                'value' => 10,
            ],
        ]);
        $quest2 = $this->app['cubicle.quests.definition.questbuilder']->build([
            'id' => 1,
            'task' => [
                'id' => 1,
                'type' => 'reject-quests',
                'operator' => 'more-than',
                'value' => 10,
            ],
        ]);
        $this->app['cubicle.quests.quests'] = [$quest, $quest2];
        $this->app['cubicle.quests.active.quests'] = [];
    }

    public function testInitialize()
    {
        $this->app->boot();

        $listeners = $this->app['dispatcher']->getListeners();
        $this->app['cubicle.quests.initializer']->initialize(1);

        $this->assertSame($listeners, $this->app['dispatcher']->getListeners());
    }

    /**
     * @dataProvider transitionProvider
     */
    public function testTransitionQuest($initialState, $transition, $expectedState)
    {
        $this->app->boot();

        $quest = new MockQuest($this->app['cubicle.quests.quests'][0], 1, $initialState);

        /** @var Workflow $workflow */
        $workflow = $this->app['cubicle.quests.workflow'];
        $this->assertTrue($workflow->can($quest, $transition));
        $workflow->apply($quest, $transition);

        $this->assertSame($expectedState, $quest->getState());
    }

    public function transitionProvider()
    {
        return [
            [QuestDefinitionInterface::STATE_AVAILABLE, QuestDefinitionInterface::TRANSITION_START, QuestDefinitionInterface::STATE_IN_PROGRESS],
            [QuestDefinitionInterface::STATE_IN_PROGRESS, QuestDefinitionInterface::TRANSITION_COMPLETE, QuestDefinitionInterface::STATE_COMPLETED],
            [QuestDefinitionInterface::STATE_COMPLETED, QuestDefinitionInterface::TRANSITION_COLLECT_REWARD, QuestDefinitionInterface::STATE_FINISHED],
            [QuestDefinitionInterface::STATE_AVAILABLE, QuestDefinitionInterface::TRANSITION_REJECT, QuestDefinitionInterface::STATE_REJECTED],
            [QuestDefinitionInterface::STATE_IN_PROGRESS, QuestDefinitionInterface::TRANSITION_ABORT, QuestDefinitionInterface::STATE_REJECTED],
        ];
    }

    public function testProgress()
    {
        $quest = new MockQuest($this->app['cubicle.quests.quests'][1], 1, QuestDefinitionInterface::STATE_IN_PROGRESS);
        $eventQuest = new MockQuest($this->app['cubicle.quests.quests'][0], 1, QuestDefinitionInterface::STATE_AVAILABLE);

        $this->app->boot();
        $this->app['cubicle.quests.listener.progress']->registerQuest($quest);

        /** @var Workflow $workflow */
        $workflow = $this->app['cubicle.quests.workflow'];
        $this->assertFalse($workflow->can($quest, QuestDefinitionInterface::TRANSITION_COMPLETE));

        $eventName = sprintf('workflow.%s.announce.%s', QuestDefinitionInterface::WORKFLOW_NAME, QuestDefinitionInterface::TRANSITION_REJECT);
        $event = new Event($eventQuest, new Marking(), new Transition(QuestDefinitionInterface::TRANSITION_REJECT, '', ''));
        for ($i = 0; $i < 11; $i++) {
            $this->assertSame(QuestDefinitionInterface::STATE_IN_PROGRESS, $quest->getState());
            $this->app['dispatcher']->dispatch($eventName, $event);
        }

        $this->assertSame(QuestDefinitionInterface::STATE_COMPLETED, $quest->getState());
    }
}
