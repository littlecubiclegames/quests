<?php declare(strict_types=1);

namespace LittleCubicleGames\Quests\Entity;

interface QuestInterface
{
    public function setState(string $state);
    public function getState(): string;

    public function getQuestId();

    public function getSlotId();

    /**
     * Method must return a map of taskId to progress
     */
    public function getProgressMap(): array;

    public function getTask($taskId): TaskInterface;
}
