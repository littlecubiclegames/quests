<?php declare(strict_types=1);

namespace LittleCubicleGames\Quests\Progress;

use LittleCubicleGames\Quests\Progress\Functions\HandlerFunctionInterface;

class ProgressFunctionBuilder implements ProgressFunctionBuilderInterface
{
    /** @var ProgressFunctionBuilderInterface[] */
    private $builders;

    public function __construct(array $builders)
    {
        $this->builders = $builders;
    }

    public function build($taskName): HandlerFunctionInterface
    {
        foreach ($this->builders as $builder) {
            $progressFunction = $builder->build($taskName);
            if ($progressFunction) {
                return $progressFunction;
            }
        }

        throw new \InvalidArgumentException(sprintf('No progress function implemented for task name: %s', $taskName));
    }
}
