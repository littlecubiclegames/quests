<?php declare(strict_types=1);

namespace LittleCubicleGames\Tests\Quests\Integration;

use LittleCubicleGames\Quests\Workflow\QuestDefinitionInterface;
use LittleCubicleGames\Tests\Quests\Mock\Entity\MockQuest;
use LittleCubicleGames\Tests\Quests\Mock\Initialization\MockQuestBuilder;
use LittleCubicleGames\Tests\Quests\Mock\Reward\MockCollector;
use Symfony\Component\Workflow\Event\Event;
use Symfony\Component\Workflow\Marking;
use Symfony\Component\Workflow\Transition;
use Symfony\Component\Workflow\Workflow;

class WorkflowTest extends AbstractIntegrationTest
{
    protected function setUp()
    {
        parent::setUp();

        $this->app['cubicle.quests.quests'] = [[
            'id' => 0,
            'task' => [
                'id' => 1,
                'type' => 'reject-quests',
                'operator' => 'less-than',
                'value' => 10,
            ],
        ], [
            'id' => 1,
            'task' => [
                'id' => 1,
                'type' => 'reject-quests',
                'operator' => 'more-than',
                'value' => 10,
            ],
        ], [
            'id' => 2,
            'task' => [
                'id' => 1,
                'type' => 'reject-quests',
                'operator' => 'less-than',
                'value' => 10,
            ],
            'rewards' => [
                [
                    'type' => 'mock',
                ],
            ],
        ]];
        $this->app['cubicle.quests.active.quests'] = [];
        $this->app['cubicle.quests.initializer.questbuilder'] = new MockQuestBuilder();
        $this->app['cubicle.quests.rewards.collectors'] = [
            new MockCollector(),
        ];
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
    public function testTransitionQuest($initialState, $transition, $expectedState, $questId)
    {
        $this->app->boot();

        $questData = $this->app['cubicle.quests.registry']->getQuest($questId);
        $quest = new MockQuest($questData, 1, $initialState, 'slot1');

        /** @var Workflow $workflow */
        $workflow = $this->app['cubicle.quests.workflow'];
        $this->assertTrue($workflow->can($quest, $transition));
        $workflow->apply($quest, $transition);

        $this->assertSame($expectedState, $quest->getState());
    }

    public function transitionProvider()
    {
        return [
            [QuestDefinitionInterface::STATE_AVAILABLE, QuestDefinitionInterface::TRANSITION_START, QuestDefinitionInterface::STATE_IN_PROGRESS, 1],
            [QuestDefinitionInterface::STATE_IN_PROGRESS, QuestDefinitionInterface::TRANSITION_COMPLETE, QuestDefinitionInterface::STATE_COMPLETED, 2],
            [QuestDefinitionInterface::STATE_COMPLETED, QuestDefinitionInterface::TRANSITION_COLLECT_REWARD, QuestDefinitionInterface::STATE_FINISHED, 2],
            [QuestDefinitionInterface::STATE_AVAILABLE, QuestDefinitionInterface::TRANSITION_REJECT, QuestDefinitionInterface::STATE_REJECTED, 2],
            [QuestDefinitionInterface::STATE_IN_PROGRESS, QuestDefinitionInterface::TRANSITION_ABORT, QuestDefinitionInterface::STATE_REJECTED, 2],
        ];
    }

    public function testProgress()
    {
        $quest = new MockQuest($this->app['cubicle.quests.registry']->getQuest(1), 1, QuestDefinitionInterface::STATE_IN_PROGRESS, 'slot1');
        $eventQuest = new MockQuest($this->app['cubicle.quests.registry']->getQuest(0), 1, QuestDefinitionInterface::STATE_AVAILABLE, 'slot2');

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

        $this->assertSame(QuestDefinitionInterface::STATE_FINISHED, $quest->getState());
    }
}
