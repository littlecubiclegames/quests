<?php

namespace LittleCubicleGames\Quests\Entity;

interface QuestInterface
{
    public function setState(string $state);
    public function getState() : string;
}
