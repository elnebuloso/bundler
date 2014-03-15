<?php
namespace Bundler\Task;

use Exception;

/**
 * Class AbstractPublicTask
 *
 * @package Bundler\Task
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
abstract class AbstractPublicTask extends AbstractTask {

    /**
     * @var string
     */
    protected $_compressor;

    /**
     * @var string
     */
    protected $_java;

    /**
     * @var string
     */
    protected $_content;

    /**
     * @var string
     */
    protected $_destinationMax;

    /**
     * @var string
     */
    protected $_destinationMin;

    /**
     * @var string
     */
    protected $_thirdParty;

    /**
     * @param string $compressor
     */
    public function setCompressor($compressor) {
        $this->_compressor = $compressor;
    }

    /**
     * @param string $java
     */
    public function setJava($java) {
        $this->_java = $java;
    }

    /**
     * @return void
     * @throws Exception
     */
    public function bundle() {
        parent::bundle();

        $this->_thirdParty = realpath(__DIR__ . '/../../../third-party');

        if($this->_thirdParty === false) {
            throw new Exception("Bundler missing it Third Party Tools at {$this->_thirdParty}");
        }
    }
}