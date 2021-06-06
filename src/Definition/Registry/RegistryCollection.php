<?php declare(strict_types = 1);

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

    public function getQuest(int $id): Quest
    {
        foreach ($this->registries as $registry) {
            if ($registry->supports($id)) {
                return $registry->getQuest($id);
            }
        }

        throw new \Exception(sprintf('Invalid Quest Id: %s', $id));
    }

    public function getNextQuest(int $user, Slot $slot, ?QuestInterface $quest = null): ?Quest
    {
        foreach ($this->registries as $registry) {
            if (isset($quest) && $registry->supports($quest->getQuestId())) {
                return $registry->getNextQuest($user, $slot, $quest);
            }
        }

        return null;
    }
}
