<?php
namespace Bundler\Compiler;

/**
 * Class YuiCompressor
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class YuiCompressor implements Compiler {

    /**
     * @var string
     */
    private $execCommand;

    /**
     * @var int
     */
    private $lineBreak = 5000;

    /**
     * @return self
     */
    public function __construct() {
        $this->execCommand = realpath(__DIR__ . '/../../../bin/yuicompressor');
    }

    /**
     * @param int $lineBreak
     */
    public function setLineBreak($lineBreak) {
        $this->lineBreak = $lineBreak;
    }

    /**
     * @return int
     */
    public function getLineBreak() {
        return $this->lineBreak;
    }

    /**
     * @param string $source
     * @param string $destination
     * @return int
     */
    public function compile($source, $destination) {
        $command[] = $this->execCommand;
        $command[] = "--type css";
        $command[] = "--line-break {$this->getLineBreak()}";
        $command[] = "-o";
        $command[] = $destination;
        $command[] = $source;
        $command = implode(" ", $command);

        exec($command);
    }
}