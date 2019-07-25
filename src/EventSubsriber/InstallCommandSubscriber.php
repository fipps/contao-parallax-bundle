<?php
/**
 *  Copyright Information
 *
 * @copyright: 2019 agentur fipps e.K.
 * @author   : Arne Borchert <arne.borchert@fipps.de>
 * @license  : LGPL 3.0+
 */

namespace Fipps\ParallaxBundle\EventSubsriber;

use \Exception;
use Contao\CoreBundle\Command\SymlinksCommand;
use Contao\CoreBundle\Framework\ContaoFramework;
use Contao\Database;
use Symfony\Component\Console\ConsoleEvents;
use Symfony\Component\Console\Event\ConsoleTerminateEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\KernelInterface;

class InstallCommandSubscriber implements EventSubscriberInterface
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
            $isConverted = false;
            if ($database->fieldExists('hAlign', 'tl_article')) {
                $sql    = "SELECT hAlign FROM tl_article WHERE hAlign IN ('left','right','center','') AND hAlign != '0' LIMIT 1";
                $result = $database->prepare($sql)->execute();
                if ($result->fetchAssoc() !== false) {
                    $output->writeln('Start Converting DB Entries "hAlign" for Parallax Update');
                    $isConverted = true;

                    $database->beginTransaction();
                    $sql = "UPDATE tl_article SET hAlign = ? WHERE hAlign = ? ";
                    $database->prepare($sql)->execute([50, 'center']);
                    $database->prepare($sql)->execute([0, 'left']);
                    $database->prepare($sql)->execute([100, 'right']);
                    $database->prepare($sql)->execute([50, '']);
                    $database->commitTransaction();
                }
            }

            if ($database->fieldExists('vAlign', 'tl_article')) {
                $sql    = "SELECT vAlign FROM tl_article WHERE vAlign IN ('top','bottom','center','') AND vAlign != '0' LIMIT 1";
                $result = $database->prepare($sql)->execute();
                if ($result->fetchAssoc() !== false) {
                    $output->writeln('Start Converting DB Entries "vAlign" for Parallax Update');
                    $isConverted = true;

                    $database->beginTransaction();
                    $sql = "UPDATE tl_article SET vAlign = ? WHERE vAlign = ? ";
                    $database->prepare($sql)->execute([50, 'center']);
                    $database->prepare($sql)->execute([0, 'top']);
                    $database->prepare($sql)->execute([100, 'bottom']);
                    $database->prepare($sql)->execute([50, '']);
                    $database->commitTransaction();
                }

            }

            if ($isConverted) {
                $output->writeln('Finished Converting DB Entries');
            }

        } catch (Exception $exception) {
            $output->writeln($exception->getMessage());
        }
    }

}
