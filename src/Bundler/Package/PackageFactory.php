<?php
namespace Bundler\Package;

use Bundler\Bundler;
use Bundler\Compiler\CompilerFactory;
use Exception;
use Symfony\Component\Console\Output\OutputInterface;
use Zend\Log\LoggerInterface;

/**
 * Class PackageFactory
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class PackageFactory {

    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @param LoggerInterface $logger
     */
    public function setLogger($logger) {
        $this->logger = $logger;
    }

    /**
     * @return LoggerInterface
     */
    public function getLogger() {
        return $this->logger;
    }

    /**
     * @param OutputInterface $output
     */
    public function setOutput($output) {
        $this->output = $output;
    }

    /**
     * @return OutputInterface
     */
    public function getOutput() {
        return $this->output;
    }

    /**
     * @param $type
     * @param $name
     * @param $root
     * @param array $config
     * @return Package
     * @throws Exception
     */
    public function create($type, $name, $root, array $config) {
        $package = null;

        switch($type) {
            case Bundler::TYPE_FILES:
                $package = new FilePackage();
                $package->setName($name);
                $package->setRoot($root);
                $package->setTarget($config['target']);
                $package->setIncludes($config['include']);
                $package->setExcludes($config['exclude']);
                $package->setVersion($config['version']);
                break;

            case Bundler::TYPE_JAVASCRIPT:
                $package = new JavascriptPackage();
                $package->setName($name);
                $package->setRoot($root . '/' . $config['public']);
                $package->setTarget($config['target']);
                $package->setPublic($config['public']);
                $package->setIncludes($config['include']);

                $compilerFactory = new CompilerFactory();

                foreach($config['compilers'] as $type => $config) {
                    $package->addCompiler($compilerFactory->create($type, $config));
                }

                break;

            case Bundler::TYPE_STYLESHEET:
                $package = new StylesheetPackage();
                $package->setName($name);
                $package->setRoot($root . '/' . $config['public']);
                $package->setTarget($config['target']);
                $package->setPublic($config['public']);
                $package->setIncludes($config['include']);

                $compilerFactory = new CompilerFactory();

                foreach($config['compilers'] as $type => $config) {
                    $package->addCompiler($compilerFactory->create($type, $config));
                }

                break;

            default:
                throw new Exception('invalid package to create for type: ' . $type);
        }

        $package->setLogger($this->getLogger());
        $package->setOutput($this->getOutput());

        return $package;
    }
}