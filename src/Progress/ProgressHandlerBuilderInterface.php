<?php

namespace LittleCubicleGames\Quests\Progress;

interface ProgressHandlerBuilderInterface
{
    public function build($taskName) : callable;
}
