<?php declare(strict_types = 1);

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

    protected function configure(): void
    {
        $this
            ->setName('littlecubiclegames:quests:validation')
            ->setDescription('Validate all quests in the registry')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $hasError = false;
        foreach ($this->quests as $questData) {
            try {
                $quest = $this->questBuilder->build($questData);

                $taskMap = $quest->getTask()->getTaskIdTypes();
                $attributesMap = $quest->getTask()->getTaskIdAttributes();
                foreach ($taskMap as $taskId => $type) {
                    $this->progressFunctionBuilder->build($type, $attributesMap[$taskId]);
                }

                if ($output->isDebug()) {
                    $output->writeln(sprintf('Quest %s is <info>ok</info>', $quest->getId()));
                }
            } catch (\Exception $e) {
                $hasError = true;
                $questId = $questData['id'] ?? 'unknown';
                $output->writeln(sprintf('<error>Validation failed for quest: %s. %s</error>', $questId, $e->getMessage()));
            }
        }

        if (!$hasError) {
            $output->writeln(sprintf('<info>Quest validation done: %s quest(s) ok</info>', count($this->quests)));

            return 0;
        }

        $output->writeln('Quest validation done');

        return 1;
    }
}
