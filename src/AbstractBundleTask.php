<?php
/**
 * Class AbstractBundleTask
 *
 * @package PhingCommons\Bundle
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
abstract class AbstractBundleTask extends Task {

    /**
     * @var int
     */
    protected $_verbose;



    /**
     * @param $verbose
     */
    public function setVerbose($verbose) {
        $this->_verbose = (int) $verbose;
    }


    /**
     * @throws BuildException
     */
    public function main() {




        $this->log("");
        $this->log("manifest:  {$this->_manifest}");
        $this->log("folder:    {$this->_folder}");

        $this->_readManifest();
    }




}