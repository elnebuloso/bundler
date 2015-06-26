<?php
namespace Bundler;

use Symfony\Component\Console\Output\OutputInterface;
use Zend\Log\LoggerInterface;

/**
 * Class BundlerLogger
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class BundlerLogger
{

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var OutputInterface
     */
    private $consoleOutput;

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger($logger = null)
    {
        $this->logger = $logger;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger()
    {
        return $this->logger;
    }

    /**
     * @param OutputInterface $consoleOutput
     */
    public function setConsoleOutput($consoleOutput = null)
    {
        $this->consoleOutput = $consoleOutput;
    }

    /**
     * @return OutputInterface
     */
    public function getConsoleOutput()
    {
        return $this->consoleOutput;
    }

    /**
     * @param string $message
     */
    public function logInfo($message)
    {
        if (!is_null($this->getLogger())) {
            $this->logger->info($message);
        }

        if (!is_null($this->getConsoleOutput())) {
            $this->consoleOutput->writeln("<comment>" . $message . "</comment>");
        }
    }

    /**
     * @param string $message
     */
    public function logDebug($message)
    {
        if (!is_null($this->getLogger())) {
            $this->logger->debug($message);
        }

        if (!is_null($this->getConsoleOutput())) {
            $this->consoleOutput->writeln("<info>>>> " . $message . "</info>");
        }
    }
}
