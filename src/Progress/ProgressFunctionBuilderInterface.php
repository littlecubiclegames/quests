<?php

namespace LittleCubicleGames\Quests\Progress;

interface ProgressFunctionBuilderInterface
{
    public function build($taskName) : callable;
}