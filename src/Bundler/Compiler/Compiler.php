<?php
namespace Bundler\Compiler;

/**
 * Class Compiler
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class Compiler {

    /**
     * @var string
     */
    private $command;

    /**
     * @param string $command
     * @throws CompilerException
     */
    public function __construct($command) {
        $this->command = trim($command);

        if(empty($this->command)) {
            throw new CompilerException('missing command for compiler', 2000);
        }
    }

    /**
     * @return void
     */
    public function compile() {
    }
}