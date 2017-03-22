<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
namespace LittleCubicleGames\Tests\Quests\Integration;

use LittleCubicleGames\Quests\Workflow\QuestDefinitionInterface;
use LittleCubicleGames\Tests\Quests\Mock\Entity\MockQuest;
use LittleCubicleGames\Tests\Quests\Mock\Initialization\MockQuestBuilder;
use Symfony\Component\Workflow\Event\Event;
use Symfony\Component\Workflow\Marking;
use Symfony\Component\Workflow\Transition;
use Symfony\Component\Workflow\Workflow;

class WorkflowTest extends AbstractIntegrationTest
{
    protected function setUp()
    {
        parent::setUp();
        $this->app['cubicle.quests.quests'] = array(array('id' => 0, 'task' => array('id' => 1, 'type' => 'reject-quests', 'operator' => 'less-than', 'value' => 10)), array('id' => 1, 'task' => array('id' => 1, 'type' => 'reject-quests', 'operator' => 'more-than', 'value' => 10)));
        $this->app['cubicle.quests.active.quests'] = array();
        $this->app['cubicle.quests.initializer.questbuilder'] = new MockQuestBuilder();
    }
    public function testInitialize()
    {
        $this->app->boot();
        $this->app['cubicle.quests.initializer.questbuilder'] = new MockQuestBuilder();
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
        $questData = $this->app['cubicle.quests.registry']->getQuest(0);
        $quest = new MockQuest($questData, 1, $initialState, 'slot1');
        /** @var Workflow $workflow */
        $workflow = $this->app['cubicle.quests.workflow'];
        $this->assertTrue($workflow->can($quest, $transition));
        $workflow->apply($quest, $transition);
        $this->assertSame($expectedState, $quest->getState());
    }
    public function transitionProvider()
    {
        return array(array(QuestDefinitionInterface::STATE_AVAILABLE, QuestDefinitionInterface::TRANSITION_START, QuestDefinitionInterface::STATE_IN_PROGRESS), array(QuestDefinitionInterface::STATE_IN_PROGRESS, QuestDefinitionInterface::TRANSITION_COMPLETE, QuestDefinitionInterface::STATE_COMPLETED), array(QuestDefinitionInterface::STATE_COMPLETED, QuestDefinitionInterface::TRANSITION_COLLECT_REWARD, QuestDefinitionInterface::STATE_FINISHED), array(QuestDefinitionInterface::STATE_AVAILABLE, QuestDefinitionInterface::TRANSITION_REJECT, QuestDefinitionInterface::STATE_REJECTED), array(QuestDefinitionInterface::STATE_IN_PROGRESS, QuestDefinitionInterface::TRANSITION_ABORT, QuestDefinitionInterface::STATE_REJECTED));
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
        $this->assertSame(QuestDefinitionInterface::STATE_COMPLETED, $quest->getState());
    }
}
