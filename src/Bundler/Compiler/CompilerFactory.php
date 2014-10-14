<?php
namespace Bundler\Compiler;

use Exception;

/**
 * Class CompilerFactory
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class CompilerFactory {

    /**
     * @param string $type
     * @param array $config
     * @return Compiler
     * @throws Exception
     */
    public function create($type, array $config) {
        switch($type) {
            case Compiler::TYPE_GOOGLE_CLOSURE_COMPILER:
                $compiler = new GoogleClosureCompiler();
                $compiler->setCompilationLevel($config['compilationLevel']);
                $compiler->setWarningLevel($config['warningLevel']);
                break;

            case Compiler::TYPE_YUI_COMPRESSOR:
                $compiler = new YuiCompressor();
                $compiler->setLineBreak($config['lineBreak']);
                break;

            default:
                throw new Exception('invalid compiler type: ' . $type);
        }

        return $compiler;
    }
}