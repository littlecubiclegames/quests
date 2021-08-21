<?php declare(strict_types=1);

namespace LittleCubicleGames\Quests\Helper;

use Doctrine\Common\Cache\Cache;

class ArrayCache implements Cache
{
    /** @var array<mixed, mixed> */
    private $cache = [];

    public function fetch($id)
    {
        return $this->cache[$id] ?? null;
    }

    public function contains($id)
    {
        return isset($this->cache[$id]);
    }

    public function save($id, $data, $lifeTime = 0)
    {
        $this->cache[$id] = $data;

        return true;
    }

    public function delete($id)
    {
        if ($this->contains($id)) {
            unset($this->cache[$id]);

            return true;
        }

        return false;
    }

    public function getStats()
    {
        return null;
    }
}
