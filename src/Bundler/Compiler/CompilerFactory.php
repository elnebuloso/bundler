<?php
namespace Bundler\Compiler;

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
     * @throws CompilerFactoryException
     */
    public static function create($type, array $config) {
        switch($type) {
            case Compiler::TYPE_GOOGLE_CLOSURE_COMPILER:
                $compiler = new GoogleClosureCompiler();
                $compiler->setCompilationLevel($config['compilationLevel']);
                $compiler->setWarningLevel($config['warningLevel']);

                return $compiler;
                break;

            case Compiler::TYPE_YUI_COMPRESSOR:
                $compiler = new YuiCompressor();
                $compiler->setLineBreak($config['lineBreak']);

                return $compiler;
                break;
        }

        throw new CompilerFactoryException('invalid compiler type: ' . $type, 2000);
    }
}