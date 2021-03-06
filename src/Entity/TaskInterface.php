<?php declare(strict_types=1);

namespace LittleCubicleGames\Quests\Entity;

interface TaskInterface
{
    public function updateProgress(int $progress): void;
    public function getProgress(): int;
}
