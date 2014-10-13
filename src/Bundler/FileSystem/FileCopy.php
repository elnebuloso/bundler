<?php
namespace Bundler\FileSystem;

use Exception;

/**
 * Class FileCopy
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class FileCopy {

    /**
     * @var string
     */
    const METHOD_NATIVE = 'native';

    /**
     * @var string
     */
    const METHOD_PHP = 'php';

    /**
     * @var string
     */
    private $copyMethod;

    /**
     * @return FileCopy
     */
    public function __construct() {
        $this->copyMethod = (shell_exec('which cp')) ? self::METHOD_NATIVE : self::METHOD_PHP;
    }

    /**
     * @param string $source
     * @param string $destination
     * @throws Exception
     */
    public function copyFile($source, $destination) {
        @mkdir(dirname($destination), 0777, true);

        switch($this->copyMethod) {
            case self::METHOD_NATIVE:
                $this->copyFileNative($source, $destination);
                break;

            case self::METHOD_PHP:
                $this->copyFilePhp($source, $destination);
                break;
        }
    }

    /**
     * @param string $source
     * @param string $destination
     */
    private function copyFileNative($source, $destination) {
        shell_exec("cp -r $source $destination");
    }

    /**
     * @param string $source
     * @param string $destination
     */
    private function copyFilePhp($source, $destination) {
        $sourceHandle = fopen($source, 'r');
        $destinationHandle = fopen($destination, 'w+');

        stream_copy_to_stream($sourceHandle, $destinationHandle);

        fclose($sourceHandle);
        fclose($destinationHandle);
    }
}