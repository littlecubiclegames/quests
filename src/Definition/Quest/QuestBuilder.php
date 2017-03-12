<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
namespace LittleCubicleGames\Quests\Definition\Quest;

use LittleCubicleGames\Quests\Definition\Reward\RewardBuilder;
use LittleCubicleGames\Quests\Definition\Task\TaskBuilder;

class QuestBuilder
{
    /** @var TaskBuilder */
    private $taskBuilder;
    /** @var RewardBuilder */
    private $rewardBuilder;
    public function __construct(TaskBuilder $taskBuilder, RewardBuilder $rewardBuilder)
    {
        $this->taskBuilder = $taskBuilder;
        $this->rewardBuilder = $rewardBuilder;
    }
    public function build(array $data)
    {
        $task = $this->taskBuilder->build($data['task']);
        $reward = $this->rewardBuilder->build($data);

        return new Quest($data['id'], $task, $reward);
    }
}
