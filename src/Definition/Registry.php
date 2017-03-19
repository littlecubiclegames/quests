<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
namespace LittleCubicleGames\Quests\Definition;

use Doctrine\Common\Cache\Cache;
use LittleCubicleGames\Quests\Definition\Quest\Quest;
use LittleCubicleGames\Quests\Definition\Quest\QuestBuilder;

class Registry
{
    /** @var array[] */
    private $quests;
    /** @var QuestBuilder */
    private $questBuilder;
    /** @var Cache */
    private $cache;
    public function __construct(array $quests, QuestBuilder $questBuilder, Cache $cache)
    {
        $this->quests = $quests;
        $this->questBuilder = $questBuilder;
        $this->cache = $cache;
    }
    public function getQuest($id)
    {
        if (!isset($this->quests[$id])) {
            throw new \Exception(sprintf('Invalid Quest Id: %s', $id));
        }
        if (!$this->cache->contains($id)) {
            $this->cache->save($id, $this->questBuilder->build($this->quests[$id]));
        }

        return $this->cache->fetch($id);
    }
    public function getNextQuest()
    {
        return array_rand($this->quests);
    }
}
