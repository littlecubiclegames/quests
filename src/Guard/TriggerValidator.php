<?php

namespace LittleCubicleGames\Quests\Guard;

use LittleCubicleGames\Quests\Definition\Quest\Quest;
use LittleCubicleGames\Quests\Definition\Slot\Slot;
use LittleCubicleGames\Quests\Entity\QuestInterface;
use LittleCubicleGames\Quests\Initialization\QuestBuilderInterface;
use LittleCubicleGames\Quests\Progress\Functions\InitProgressHandlerFunctionInterface;
use LittleCubicleGames\Quests\Progress\ProgressFunctionBuilderInterface;
use LittleCubicleGames\Quests\Progress\ProgressHandler;

class TriggerValidator
{
    /** @var QuestBuilderInterface */
    private $questEntityBuilder;
    /** @var ProgressFunctionBuilderInterface */
    private $progressFunctionBuilder;
    /** @var ProgressHandler */
    private $questProgressHandler;

    public function __construct(QuestBuilderInterface $questEntityBuilder, ProgressFunctionBuilderInterface $progressFunctionBuilder, ProgressHandler $questProgressHandler)
    {
        $this->questEntityBuilder = $questEntityBuilder;
        $this->progressFunctionBuilder = $progressFunctionBuilder;
        $this->questProgressHandler = $questProgressHandler;
    }

    public function canTrigger(Quest $questData, Slot $slot, $user)
    {
        $triggerMap = $questData->getTrigger()->getTaskIdTypes();
        $attributesMap = $questData->getTrigger()->getTaskIdAttributes();

        /** @var QuestInterface $quest */
        $quest = $this->questEntityBuilder->buildQuest($questData, $slot, $user);
        foreach ($triggerMap as $taskId => $type) {
            $handlerFunction = $this->progressFunctionBuilder->build($type, $attributesMap[$taskId]);
            if (!$handlerFunction instanceof InitProgressHandlerFunctionInterface) {
                throw new \InvalidArgumentException('Invalid trigger handler provided. Can only use instances of: ' . InitProgressHandlerFunctionInterface::class);
            }

            $this->questProgressHandler->initProgress($quest, $taskId, $handlerFunction);
        }

        return $questData->getTrigger()->isFinished($quest->getProgressMap());
    }
}
