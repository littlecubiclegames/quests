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

    public function build(string $taskName, array $attributes = []): HandlerFunctionInterface
    {
        foreach ($this->builders as $builder) {
            $progressFunction = $builder->build($taskName, $attributes);
            if (isset($progressFunction)) {
                return $progressFunction;
            }
        }

        throw new \InvalidArgumentException(sprintf('No progress function implemented for task name: %s', $taskName));
    }
}
