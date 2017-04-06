<?php

namespace LittleCubicleGames\Quests\Definition\Registry;

use LittleCubicleGames\Quests\Definition\Quest\Quest;
use LittleCubicleGames\Quests\Definition\Slot\Slot;
use LittleCubicleGames\Quests\Entity\QuestInterface;

class RegistryCollection implements RegistryInterface
{
    /** @var CollectibleRegistryInterface[] */
    private $registries;
    public function __construct(array $registries)
    {
        $this->registries = $registries;
    }
    public function getQuest($id)
    {
        foreach ($this->registries as $registry) {
            if ($registry->supports($id)) {
                $ret158d2465554d1c = $registry->getQuest($id);
                if (!$ret158d2465554d1c instanceof Quest) {
                    throw new \InvalidArgumentException('Argument returned must be of the type Quest, ' . (gettype($ret158d2465554d1c) == 'object' ? get_class($ret158d2465554d1c) : gettype($ret158d2465554d1c)) . ' given');
                }

                return $ret158d2465554d1c;
            }
        }
        throw new \Exception(sprintf('Invalid Quest Id: %s', $id));
    }
    public function getNextQuest($user, Slot $slot, QuestInterface $quest = null)
    {
        foreach ($this->registries as $registry) {
            if ($registry->supports($quest->getQuestId())) {
                $ret158d2465557aba = $registry->getNextQuest($user, $slot, $quest);
                if (!$ret158d2465557aba instanceof Quest) {
                    throw new \InvalidArgumentException('Argument returned must be of the type Quest, ' . (gettype($ret158d2465557aba) == 'object' ? get_class($ret158d2465557aba) : gettype($ret158d2465557aba)) . ' given');
                }

                return $ret158d2465557aba;
            }
        }
    }
}
