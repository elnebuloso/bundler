<?php
namespace Bundler\Package;

use Bundler\BundlerLogger;
use Bundler\Tools\Benchmark;

/**
 * Class AbstractPackage
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
abstract class AbstractPackage implements PackageInterface
{
    /**
     * @var BundlerLogger
     */
    private $bundlerLogger;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $root;

    /**
     * @var string
     */
    private $target;

    /**
     * @var array
     */
    private $includes;

    /**
     * @return self
     */
    public function __construct()
    {
        $this->includes = [];
        $this->bundlerLogger = new BundlerLogger();
    }

    /**
     * @param BundlerLogger $bundlerLogger
     */
    public function setBundlerLogger(BundlerLogger $bundlerLogger)
    {
        $this->bundlerLogger = $bundlerLogger;
    }

    /**
     * @return BundlerLogger
     */
    public function getBundlerLogger()
    {
        return $this->bundlerLogger;
    }

    /**
     * @param string $name
     * @throws PackageException
     */
    public function setName($name)
    {
        $this->name = trim($name);

        if (empty($this->name)) {
            throw new PackageException('the package name cannot be empty', 3000);
        }
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $root
     * @throws PackageException
     */
    public function setRoot($root)
    {
        $this->root = realpath($root);

        if ($this->root === false) {
            throw new PackageException('invalid root path: ' . $root, 3001);
        }
    }

    /**
     * @return string
     */
    public function getRoot()
    {
        return $this->root;
    }

    /**
     * @param string $target
     */
    public function setTarget($target)
    {
        $this->target = $target;
    }

    /**
     * @return string
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * @param array $includes
     */
    public function setIncludes(array $includes)
    {
        $this->includes = $includes;
    }

    /**
     * @return array
     */
    public function getIncludes()
    {
        return $this->includes;
    }

    /**
     * @return void
     */
    public function bundle()
    {
        $this->getBundlerLogger()->logInfo("bundling package: {$this->getName()}");

        $benchmark = new Benchmark();
        $benchmark->start();

        $this->bundlePackage();

        $benchmark->stop();

        $this->getBundlerLogger()->logInfo("bundling package: {$this->getName()} in {$benchmark->getTime()} seconds");
    }

    /**
     * @return void
     */
    abstract protected function bundlePackage();
}
