<?php
namespace Bundler\Compiler;

use Exception;

/**
 * Class GoogleClosureCompiler
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class GoogleClosureCompiler implements Compiler {

    /**
     * @var string
     */
    private $execCommand;

    /**
     * @var string
     */
    private $compilationLevel = 'WHITESPACE_ONLY';

    /**
     * @var string
     */
    private $warningLevel = 'QUIET';

    /**
     * @throws Exception
     */
    public function __construct() {
        $path = dirname(__DIR__) . '/../../bin/google-closure-compiler';

        $this->execCommand = realpath($path);

        if($this->execCommand === false) {
            throw new Exception('invalid exec command on path: ' . $path);
        }
    }

    /**
     * @return string
     */
    public function getExecCommand() {
        return $this->execCommand;
    }

    /**
     * @param string $compilationLevel
     */
    public function setCompilationLevel($compilationLevel) {
        $this->compilationLevel = $compilationLevel;
    }

    /**
     * @return string
     */
    public function getCompilationLevel() {
        return $this->compilationLevel;
    }

    /**
     * @param string $warningLevel
     */
    public function setWarningLevel($warningLevel) {
        $this->warningLevel = $warningLevel;
    }

    /**
     * @return string
     */
    public function getWarningLevel() {
        return $this->warningLevel;
    }

    /**
     * @param string $source
     * @param string $destination
     * @return void
     */
    public function compile($source, $destination) {
        $command[] = $this->getExecCommand();
        $command[] = "--compilation_level={$this->getCompilationLevel()}";
        $command[] = "--warning_level={$this->getWarningLevel()}";
        $command[] = "--js={$source}";
        $command[] = "--js_output_file={$destination}";
        $command = implode(" ", $command);

        exec($command);
    }
}