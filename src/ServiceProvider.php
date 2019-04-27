<?php declare(strict_types=1);

namespace LittleCubicleGames\Quests;

use Doctrine\Common\Cache\ArrayCache;
use LittleCubicleGames\Quests\Command\ValidationCommand;
use LittleCubicleGames\Quests\Definition\Quest\QuestBuilder;
use LittleCubicleGames\Quests\Definition\Registry\RandomRegistry;
use LittleCubicleGames\Quests\Definition\Reward\RewardBuilder;
use LittleCubicleGames\Quests\Definition\Slot\SlotBuilder;
use LittleCubicleGames\Quests\Definition\Task\TaskBuilder;
use LittleCubicleGames\Quests\Guard\IsCompletedListener;
use LittleCubicleGames\Quests\Guard\TriggerValidator;
use LittleCubicleGames\Quests\Initialization\NextQuestListener;
use LittleCubicleGames\Quests\Initialization\QuestInitializer;
use LittleCubicleGames\Quests\Initialization\QuestStarter;
use LittleCubicleGames\Quests\Log\QuestLogListener;
use LittleCubicleGames\Quests\Progress\ProgressFunctionBuilder;
use LittleCubicleGames\Quests\Progress\ProgressHandler;
use LittleCubicleGames\Quests\Progress\ProgressListener;
use LittleCubicleGames\Quests\Progress\StateFunctionBuilder;
use LittleCubicleGames\Quests\Reward\Collect\Provider;
use LittleCubicleGames\Quests\Reward\NoRewardListener;
use LittleCubicleGames\Quests\Reward\RewardListener;
use LittleCubicleGames\Quests\Slot\StaticSlotLoader;
use LittleCubicleGames\Quests\Storage\ArrayStorage;
use LittleCubicleGames\Quests\Workflow\QuestDefinition;
use LittleCubicleGames\Quests\Workflow\QuestDefinitionInterface;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Api\EventListenerProviderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Workflow\MarkingStore\SingleStateMarkingStore;
use Symfony\Component\Workflow\Workflow;

class ServiceProvider implements ServiceProviderInterface, EventListenerProviderInterface
{
    public function register(Container $pimple): void
    {
        $pimple['cubicle.quests.autostart'] = false;

        if (!isset($pimple['cubicle.quests.logger'])) {
            $pimple['cubicle.quests.logger'] = [];
        }

        if (!isset($pimple['cubicle.quests.quests'])) {
            $pimple['cubicle.quests.quests'] = [];
        }

        if (!isset($pimple['cubicle.quests.active.quests'])) {
            $pimple['cubicle.quests.active.quests'] = [];
        }

        if (!isset($pimple['cubicle.quests.slots'])) {
            $pimple['cubicle.quests.slots'] = [];
        }

        if (!isset($pimple['cubicle.quests.rewards.collectors'])) {
            $pimple['cubicle.quests.rewards.collectors'] = [];
        }

        $pimple['cubicle.quests.definition'] = function () {
            return new QuestDefinition();
        };

        $pimple['cubicle.quests.definition.taskbuilder'] = function () {
            return new TaskBuilder();
        };

        $pimple['cubicle.quests.definition.rewardbuilder'] = function () {
            return new RewardBuilder();
        };

        $pimple['cubicle.quests.definition.slotbuilder'] = function () {
            return new SlotBuilder();
        };

        $pimple['cubicle.quests.definition.cache'] = function () {
            return new ArrayCache();
        };

        $pimple['cubicle.quests.definition.questbuilder'] = function (Container $pimple) {
            return new QuestBuilder($pimple['cubicle.quests.definition.taskbuilder'], $pimple['cubicle.quests.definition.rewardbuilder']);
        };

        $pimple['cubicle.quests.marking_store'] = function () {
            return new SingleStateMarkingStore('state');
        };

        $pimple['cubicle.quests.workflow'] = function (Container $pimple) {
            return new Workflow(
                $pimple['cubicle.quests.definition']->build(),
                $pimple['cubicle.quests.marking_store'],
                $pimple['dispatcher'],
                QuestDefinitionInterface::WORKFLOW_NAME
            );
        };

        $pimple['cubicle.quests.registry'] = function (Container $pimple) {
            return new RandomRegistry(
                $pimple['cubicle.quests.quests'],
                $pimple['cubicle.quests.definition.questbuilder'],
                $pimple['cubicle.quests.guard.triggervalidator'],
                $pimple['cubicle.quests.definition.cache'],
                'random'
            );
        };

        $pimple['cubicle.quests.guard.triggervalidator'] = function (Container $pimple) {
            return new TriggerValidator(
                $pimple['cubicle.quests.initializer.questbuilder'],
                $pimple['cubicle.quests.progress.function.builder'],
                $pimple['cubicle.quests.progress.handler']
            );
        };

        $pimple['cubicle.quests.rewards.provider'] = function (Container $pimple) {
            return new Provider($pimple['cubicle.quests.rewards.collectors']);
        };

        $pimple['cubicle.quests.listener.log'] = function (Container $pimple) {
            return new QuestLogListener($pimple['cubicle.quests.logger']);
        };

        $pimple['cubicle.quests.listener.iscompleted.guard'] = function (Container $pimple) {
            return new IsCompletedListener($pimple['cubicle.quests.registry']);
        };

        $pimple['cubicle.quests.listener.progress'] = function (Container $pimple) {
            return new ProgressListener(
                $pimple['cubicle.quests.registry'],
                $pimple['dispatcher'],
                $pimple['cubicle.quests.progress.handler'],
                $pimple['cubicle.quests.progress.function.builder']
            );
        };

        $pimple['cubicle.quests.progress.handler'] = function (Container $pimple) {
            return new ProgressHandler($pimple['cubicle.quests.workflow'], $pimple['cubicle.quests.storage']);
        };

        $pimple['cubicle.quests.progress.function.builder'] = function () {
            return new ProgressFunctionBuilder([
                new StateFunctionBuilder(),
            ]);
        };

        $pimple['cubicle.quests.slot.loader'] = function (Container $pimple) {
            return new StaticSlotLoader($pimple['cubicle.quests.slots'], $pimple['cubicle.quests.definition.slotbuilder']);
        };

        $pimple['cubicle.quests.storage'] = function (Container $pimple) {
            return new ArrayStorage($pimple['cubicle.quests.active.quests']);
        };

        $pimple['cubicle.quests.advancer'] = function (Container $pimple) {
            return new QuestAdvancer(
                $pimple['cubicle.quests.storage'],
                $pimple['cubicle.quests.workflow']
            );
        };

        $pimple['cubicle.quests.initializer'] = function (Container $pimple) {
            return new QuestInitializer($pimple['cubicle.quests.storage'], $pimple['cubicle.quests.listener.progress'], $pimple['cubicle.quests.slot.loader'], $pimple['cubicle.quests.initializer.queststarter'], $pimple['dispatcher']);
        };

        $pimple['cubicle.quests.initializer.queststarter'] = function (Container $pimple) {
            return new QuestStarter(
                $pimple['cubicle.quests.registry'],
                $pimple['cubicle.quests.initializer.questbuilder'],
                $pimple['cubicle.quests.storage'],
                $pimple['dispatcher'],
                $pimple['cubicle.quests.advancer'],
                $pimple['cubicle.quests.autostart']
            );
        };

        $pimple['cubicle.quests.initializer.questbuilder'] = function () {
            throw new \Exception('Need to implement service');
        };

        $pimple['cubicle.quests.listener.noreward'] = function (Container $pimple) {
            return new NoRewardListener($pimple['cubicle.quests.registry'], $pimple['cubicle.quests.workflow']);
        };

        $pimple['cubicle.quests.listener.reward'] = function (Container $pimple) {
            return new RewardListener($pimple['cubicle.quests.registry'], $pimple['cubicle.quests.rewards.provider']);
        };

        $pimple['cubicle.quests.listener.nextquest'] = function (Container $pimple) {
            return new NextQuestListener($pimple['cubicle.quests.slot.loader'], $pimple['cubicle.quests.initializer.queststarter']);
        };

        $pimple['cubicle.quests.command.validation'] = function (Container $pimple) {
            return new ValidationCommand($pimple['cubicle.quests.definition.questbuilder'], $pimple['cubicle.quests.progress.function.builder'], $pimple['cubicle.quests.quests']);
        };
    }

    public function subscribe(Container $app, EventDispatcherInterface $dispatcher): void
    {
        $dispatcher->addSubscriber($app['cubicle.quests.listener.iscompleted.guard']);
        $dispatcher->addSubscriber($app['cubicle.quests.listener.progress']);
        $dispatcher->addSubscriber($app['cubicle.quests.listener.log']);
        $dispatcher->addSubscriber($app['cubicle.quests.listener.noreward']);
        $dispatcher->addSubscriber($app['cubicle.quests.listener.reward']);
        $dispatcher->addSubscriber($app['cubicle.quests.listener.nextquest']);
    }
}
