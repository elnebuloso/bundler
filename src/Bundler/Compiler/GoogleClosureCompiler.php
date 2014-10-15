<?php
namespace Bundler\Compiler;

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
     * @return self
     */
    public function __construct() {
        $this->execCommand = realpath(__DIR__ . '/../../../bin/google-closure-compiler');
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
        $command[] = $this->execCommand;
        $command[] = "--compilation_level={$this->getCompilationLevel()}";
        $command[] = "--warning_level={$this->getWarningLevel()}";
        $command[] = "--js={$source}";
        $command[] = "--js_output_file={$destination}";
        $command = implode(" ", $command);

        exec($command);
    }
}