<?php
namespace Bundler\Package;

use Bundler\Compiler\CompilerFactory;

/**
 * Class JavascriptPackage
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class JavascriptPackage extends AbstractPublicPackage {

    /**
     * @param string $root
     * @param string $name
     * @param array $array
     * @return JavascriptPackage
     */
    public static function createFromArray($root, $name, array $array) {
        $package = new self($root, $name);
        $package->setPublic($array['public']);
        $package->setTarget($array['target']);
        $package->setIncludes($array['include']);

        // set the compiler
        foreach($array['compiler'] as $type => $config) {
            $package->setCompiler(CompilerFactory::create($type, $config));
        }

        return $package;
    }

    /**
     * @return string
     */
    public function getFilenameMaxFile() {
        return $this->getName() . '.max.js';
    }

    /**
     * @return string
     */
    public function getFilenameMinFile() {
        return $this->getName() . '.min.js';
    }
}