<?php
require_once 'AbstractBundleTask.php';

/**
 * Class AbstractBundlePublicTask
 *
 * @package PhingCommons\Bundle
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
abstract class AbstractBundlePublicTask extends AbstractBundleTask {

    /**
     * @var string
     */
    protected $_target;



    /**
     * @var string
     */
    protected $_compiler = 'yuicompressor';



    /**
     * @param $target
     */
    public function setTarget($target) {
        $this->_target = $target;
    }

    /**
     * @param $java
     */
    public function setJava($java) {
        $this->_java = $java;
    }

    /**
     * @param $compiler
     */
    public function setCompiler($compiler) {
        $this->_compiler = $compiler;
    }

    /**
     * @throws BuildException
     */
    public function main() {
        parent::main();
    }


}