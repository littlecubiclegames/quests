<?php declare(strict_types=1);

namespace LittleCubicleGames\Quests\Definition;

use LittleCubicleGames\Quests\Definition\Quest\Quest;
use LittleCubicleGames\Quests\Definition\Quest\QuestBuilder;

class Registry
{
    /** @var Quest[] */
    private $questCache = [];
    /** @var array[] */
    private $quests;
    /** @var QuestBuilder */
    private $questBuilder;

    public function __construct(array $quests, QuestBuilder $questBuilder)
    {
        $this->quests = $quests;
        $this->questBuilder = $questBuilder;
    }

    public function getQuest($id): Quest
    {
        if (!isset($this->quests[$id])) {
            throw new \Exception(sprintf('Invalid Quest Id: %s', $id));
        }

        if (!isset($this->questCache[$id])) {
            $this->questCache[$id] = $this->questBuilder->build($this->quests[$id]);
        }

        return $this->questCache[$id];
    }
}
