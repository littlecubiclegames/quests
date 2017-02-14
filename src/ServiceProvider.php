<?php

namespace LittleCubicleGames\Quests;

use LittleCubicleGames\Quests\Definition\Registry;
use LittleCubicleGames\Quests\Definition\Task\TaskBuilder;
use LittleCubicleGames\Quests\Guard\IsCompletedListener;
use LittleCubicleGames\Quests\Initialization\QuestInitializer;
use LittleCubicleGames\Quests\Log\QuestLogListener;
use LittleCubicleGames\Quests\Progress\ProgressFunctionBuilder;
use LittleCubicleGames\Quests\Progress\ProgressHandler;
use LittleCubicleGames\Quests\Progress\ProgressListener;
use LittleCubicleGames\Quests\Progress\StateChangeListener;
use LittleCubicleGames\Quests\Progress\StateFunctionBuilder;
use LittleCubicleGames\Quests\Storage\ArrayStorage;
use LittleCubicleGames\Quests\Workflow\QuestDefinition;
use Pimple\Container;
use Pimple\ServiceProviderInterface;
use Silex\Api\EventListenerProviderInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Workflow\MarkingStore\SingleStateMarkingStore;
use Symfony\Component\Workflow\Workflow;

class ServiceProvider implements ServiceProviderInterface, EventListenerProviderInterface
{
    public function register(Container $pimple)
    {
        if (!isset($pimple['cubicle.quests.logger'])) {
            $pimple['cubicle.quests.logger'] = [];
        }

        if (!isset($pimple['cubicle.quests.quests'])) {
            $pimple['cubicle.quests.quests'] = [];
        }

        if (!isset($pimple['cubicle.quests.active.quests'])) {
            $pimple['cubicle.quests.active.quests'] = [];
        }

        $pimple['cubicle.quests.definition'] = function () {
            return new QuestDefinition();
        };

        $pimple['cubicle.quests.definition.taskbuilder'] = function () {
            return new TaskBuilder();
        };

        $pimple['cubicle.quests.marking_store'] = function () {
            return new SingleStateMarkingStore('state');
        };

        $pimple['cubicle.quests.workflow'] = function (Container $pimple) {
            return new Workflow(
                $pimple['cubicle.quests.definition']->build(),
                $pimple['cubicle.quests.marking_store'],
                $pimple['dispatcher']
            );
        };

        $pimple['cubicle.quests.registry'] = function (Container $pimple) {
            return new Registry($pimple['cubicle.quests.quests']);
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

        $pimple['cubicle.quests.storage'] = function (Container $pimple) {
            return new ArrayStorage($pimple['cubicle.quests.active.quests']);
        };

        $pimple['cubicle.quests.initializer'] = function (Container $pimple) {
            return new QuestInitializer($pimple['cubicle.quests.storage'], $pimple['cubicle.quests.listener.progress']);
        };

        $pimple['cubicle.quests.listener.state.change'] = function (Container $pimple) {
            return new StateChangeListener($pimple['cubicle.quests.storage']);
        };
    }

    public function subscribe(Container $app, EventDispatcherInterface $dispatcher)
    {
        $dispatcher->addSubscriber($app['cubicle.quests.listener.iscompleted.guard']);
        $dispatcher->addSubscriber($app['cubicle.quests.listener.progress']);
        $dispatcher->addSubscriber($app['cubicle.quests.listener.state.change']);
        $dispatcher->addSubscriber($app['cubicle.quests.listener.log']);
    }
}
