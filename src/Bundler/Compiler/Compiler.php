<?php
namespace Bundler\Compiler;

/**
 * Class Compiler
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
interface Compiler {

    /**
     * @return string
     */
    public function getExecCommand();

    /**
     * @param string $source
     * @param string $destination
     * @return int
     */
    public function compile($source, $destination);
}