<?php declare(strict_types = 1);

namespace LittleCubicleGames\Quests\Definition\Reward;

use LittleCubicleGames\Quests\Entity\QuestInterface;
use LittleCubicleGames\Quests\Reward\Collect\Provider;

class Reward implements RewardInterface
{
    /** @var string */
    private $type;
    /** @var mixed[] */
    private $data;

    public function __construct(array $data)
    {
        if (!isset($data['type'])) {
            throw new \InvalidArgumentException('reward definition does not contain type');
        }

        $this->type = $data['type'];
        $this->data = $data;
    }

    public function collect(Provider $rewardCollectorProvider, QuestInterface $quest): void
    {
        $rewardCollectorProvider->getCollector($this)->collect($this);
    }

    public function getData(): array
    {
        return $this->data;
    }

    public function getType(): string
    {
        return $this->type;
    }
}
