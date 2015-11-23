<?php
namespace Bundler\Compiler;

/**
 * Class Compiler
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class Compiler
{
    /**
     * @var string
     */
    private $command;

    /**
     * @param string $command
     * @throws CompilerException
     */
    public function __construct($command)
    {
        $this->command = trim($command);

        if (empty($this->command)) {
            throw new CompilerException('missing command for compiler', 2000);
        }
    }

    /**
     * @param string $source
     * @param string $destination
     * @return string
     */
    public function getCommand($source, $destination)
    {
        $command = str_replace('%source%', $source, $this->command);
        $command = str_replace('%destination%', $destination, $command);

        return $command;
    }

    /**
     * @param string $source
     * @param string $destination
     * @throws CompilerException
     */
    public function compile($source, $destination)
    {
        exec($this->getCommand($source, $destination), $output, $return);

        if ($return != 0) {
            throw new CompilerException(implode(PHP_EOL, $output), 2001);
        }
    }
}
