<?php
/**
 *  Copyright Information
 *
 * @copyright: 2019 agentur fipps e.K.
 * @author   : Arne Borchert <arne.borchert@fipps.de>
 * @license  : LGPL 3.0+
 */

namespace Fipps\ParallaxBundle\EventSubsriber;


use Contao\CoreBundle\Command\SymlinksCommand;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\Database;

class InstallCommandSubscriber
{
    /**
     * @var string
     */
    private $rootDir;

    /**
     * @var Filesystem
     */
    private $fs;

    /**
     * InstallCommandSubscriber constructor.
     *
     * @param KernelInterface $kernel
     * @param ContaoFramework $framework
     */
    public function __construct(KernelInterface $kernel, ContaoFramework $framework)
    {
        $this->rootDir = dirname($kernel->getRootDir());
        $this->fs      = new Filesystem();
        $framework->initialize();
    }


    /**
     * {@inheritdoc}
     */
    public static function getSubscribedEvents()
    {
        return [
            ConsoleEvents::TERMINATE => [
                [
                    'convertDatabaseEntries',
                ],
            ],
        ];
    }

    /**
     * @param ConsoleTerminateEvent $event
     * @throws \Exception
     */
    public function convertDatabaseEntries(ConsoleTerminateEvent $event)
    {

        $output   = $event->getOutput();
        $command  = $event->getCommand();
        $exitCode = $event->getExitCode();
        $database = Database::getInstance();

        if (!($command instanceof SymlinksCommand) || $exitCode > 0) {
            return;
        }

        try {
            $fieldNames = $database->getFieldNames('tl_article');
            if (in_array('hAlign', $fieldNames)) {
                $output->writeln('Start Converting DB Entries for parallax update');

                $database->beginTransaction();
                $sql = 'UPDATE tl_article SET vAlign = ? WHERE vAlign = ? ';
                $database->prepare($sql)->execute([50, 'center']);
                $database->prepare($sql)->execute([0, 'left']);
                $database->prepare($sql)->execute([100, 'right']);
                $database->commitTransaction();
                $output->writeln('Finished Converting DB Entries');
            }
        } catch (\Exception $exception) {
            return;
        }
    }

}