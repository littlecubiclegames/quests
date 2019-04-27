<?php declare(strict_types=1);

namespace LittleCubicleGames\Quests\Entity;

interface QuestInterface
{
    public function getId(): int;
    public function setState(string $state): void;
    public function getState(): string;

    public function getQuestId(): int;

    public function getUser(): int;

    public function getSlotId(): string;

    /**
     * Method must return a map of taskId to progress
     */
    public function getProgressMap(): array;

    public function getTask(int $taskId): TaskInterface;
}
