<?php
namespace Bundler\Package;

/**
 * Class JavascriptPackage
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class JavascriptPackage extends AbstractPublicPackage {

    /**
     * @param string $name
     * @param array $array
     * @return JavascriptPackage
     */
    public static function createFromArray($name, array $array) {
        $package = new self($name);
        $package->setTo($array['to']);
        $package->setCompiler($array['compiler']);
        $package->setIncludes($array['include']);

        return $package;
    }
}