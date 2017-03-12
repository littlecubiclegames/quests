<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
namespace LittleCubicleGames\Quests\Progress;

class ProgressFunctionBuilder implements ProgressFunctionBuilderInterface
{
    /** @var ProgressFunctionBuilderInterface[] */
    private $builders;
    public function __construct(array $builders)
    {
        $this->builders = $builders;
    }
    public function build($taskName)
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
