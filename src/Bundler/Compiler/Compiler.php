<?php
namespace Bundler\Compiler;

/**
 * Class Compiler
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
interface Compiler {

    /**
     * @var string
     */
    const TYPE_GOOGLE_CLOSURE_COMPILER = 'googleClosureCompiler';

    /**
     * @var string
     */
    const TYPE_YUI_COMPRESSOR = 'yuiCompressor';

    /**
     * @param string $source
     * @param string $destination
     * @return int
     */
    public function compile($source, $destination);
}