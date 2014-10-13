<?php
namespace Bundler\Compiler;

use Exception;

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
     * @throws Exception
     */
    public function __construct() {
        $path = dirname(__DIR__) . '/../../bin/yuicompressor';

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
    }
}