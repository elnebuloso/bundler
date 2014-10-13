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
     * @var string
     */
    const TYPE_GOOGLE_CLOSURE_COMPILER = 'googleClosureCompiler';

    /**
     * @var string
     */
    const TYPE_YUI_COMPRESSOR = 'yuiCompressor';

    /**
     * @param string $type
     * @param array $config
     * @return Compiler
     * @throws Exception
     */
    public static function create($type, array $config) {
        switch($type) {
            case self::TYPE_GOOGLE_CLOSURE_COMPILER:
                $compiler = new GoogleClosureCompiler();
                $compiler->setCompilationLevel($config['compilationLevel']);
                $compiler->setWarningLevel($config['warningLevel']);

                return $compiler;

            case self::TYPE_YUI_COMPRESSOR:
                $compiler = new YuiCompressor();
                $compiler->setLineBreak($config['lineBreak']);

                return $compiler;
        }

        throw new Exception('invalid compiler type: ' . $type);
    }
}