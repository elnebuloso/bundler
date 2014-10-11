<?php
namespace Bundler\Package;

/**
 * Class StylesheetPackage
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class StylesheetPackage extends AbstractPublicPackage {

    /**
     * @param string $name
     * @param array $array
     * @return StylesheetPackage
     */
    public static function createFromArray($name, array $array) {
        $package = new self($name);
        $package->setPublic($array['public']);
        $package->setTo($array['to']);
        $package->setCompiler($array['compiler']);
        $package->setIncludes($array['include']);

        return $package;
    }
}