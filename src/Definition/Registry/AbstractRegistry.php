<?php

namespace LittleCubicleGames\Quests\Definition\Registry;

use Doctrine\Common\Cache\Cache;
use LittleCubicleGames\Quests\Definition\Quest\Quest;
use LittleCubicleGames\Quests\Definition\Quest\QuestBuilder;
use LittleCubicleGames\Quests\Definition\Slot\Slot;
use LittleCubicleGames\Quests\Guard\TriggerValidator;

abstract class AbstractRegistry implements CollectibleRegistryInterface
{
    /** @var array[] */
    protected $quests;
    /** @var QuestBuilder */
    private $questBuilder;
    /** @var TriggerValidator */
    protected $triggerValidator;
    /** @var Cache */
    private $cache;
    private $id;
    public function __construct(array $quests, QuestBuilder $questBuilder, TriggerValidator $triggerValidator, Cache $cache, $id)
    {
        $this->quests = $quests;
        $this->questBuilder = $questBuilder;
        $this->triggerValidator = $triggerValidator;
        $this->cache = $cache;
        $this->id = $id;
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
    public function supports($id)
    {
        return isset($this->quests[$id]);
    }
    public function supportsSlot(Slot $slot)
    {
        return null === $this->id || $this->id === $slot->getRegistryId();
    }
}
