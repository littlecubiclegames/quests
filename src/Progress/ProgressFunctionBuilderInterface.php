<?php declare(strict_types=1);

namespace LittleCubicleGames\Quests\Progress;

use LittleCubicleGames\Quests\Progress\Functions\HandlerFunctionInterface;

interface ProgressFunctionBuilderInterface
{
    /**
     * @param array<mixed> $attributes
     */
    public function build(string $taskName, array $attributes): ?HandlerFunctionInterface;
}
