<?php

/*
 * This code has been transpiled via TransPHPile. For more information, visit https://github.com/jaytaph/transphpile
 */
namespace LittleCubicleGames\Quests\Command;

use LittleCubicleGames\Quests\Definition\Quest\QuestBuilder;
use LittleCubicleGames\Quests\Progress\ProgressFunctionBuilderInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ValidationCommand extends Command
{
    /** @var QuestBuilder */
    private $questBuilder;
    /** @var array */
    private $quests;
    /** @var ProgressFunctionBuilderInterface */
    private $progressFunctionBuilder;
    public function __construct(QuestBuilder $questBuilder, ProgressFunctionBuilderInterface $progressFunctionBuilder, array $quests)
    {
        $this->questBuilder = $questBuilder;
        $this->progressFunctionBuilder = $progressFunctionBuilder;
        $this->quests = $quests;
        parent::__construct();
    }
    protected function configure()
    {
        $this->setName('littlecubiclegames:quests:validation')->setDescription('Validate all quests in the registry');
    }
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $hasError = false;
        foreach ($this->quests as $questData) {
            try {
                $quest = $this->questBuilder->build($questData);
                $taskMap = $quest->getTask()->getTaskIdTypes();
                foreach ($taskMap as $type) {
                    $this->progressFunctionBuilder->build($type);
                }
                if ($output->isDebug()) {
                    $output->writeln(sprintf('Quest %s is <info>ok</info>', $quest->getId()));
                }
            } catch (\Exception $e) {
                $hasError = true;
                $questId = call_user_func(function ($v1, $v2) {
                    return isset($v1) ? $v1 : $v2;
                }, @$questData['id'], @'unknown');
                $output->writeln(sprintf('<error>Validation failed for quest: %s. %s</error>', $questId, $e->getMessage()));
            }
        }
        if ($hasError) {
            $output->writeln('Quest validation done');
        } else {
            $output->writeln(sprintf('<info>Quest validation done: %s quest(s) ok</info>', count($this->quests)));
        }
    }
}
