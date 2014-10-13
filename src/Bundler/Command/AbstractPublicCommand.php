<?php
namespace Bundler\Command;

use Bundler\Package\AbstractPublicPackage;

/**
 * Class AbstractPublicCommand
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
abstract class AbstractPublicCommand extends AbstractCommand {

    /**
     * @var AbstractPublicPackage
     */
    protected $currentPackage;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var array
     */
    protected $cache;

    /**
     * @var string
     */
    protected $cacheFilename;

    /**
     * @var string
     */
    protected $destinationMax;

    /**
     * @var string
     */
    protected $destinationMin;

    /**
     * @return void
     */
    public function bundleCurrentPackage() {
        $this->content = array();
        $this->destinationMax = $this->currentPackage->getDestinationMax();
        $this->destinationMin = $this->currentPackage->getDestinationMin();

        $this->compress();
    }

    /**
     * @return void
     */
    public function createFiles() {
        // create max file
        $this->content = implode(PHP_EOL . PHP_EOL, $this->content);
        file_put_contents($this->destinationMax, $this->content);

        // compile files
        $compiler = $this->currentPackage->getCompiler();
        $compiler->compile($this->destinationMax, $this->destinationMin);

        $this->writeInfo("created file: {$this->destinationMax}");
        $this->writeInfo("created file: {$this->destinationMin}");

        $org = strlen(file_get_contents($this->destinationMax));
        $new = strlen(file_get_contents($this->destinationMin));
        $ratio = !empty($org) ? $new / $org : 0;
        $this->writeInfo("org:   {$org} bytes");
        $this->writeInfo("new:   {$new} bytes");
        $this->writeInfo("ratio: {$ratio}");

        // storing cache informations
        $this->cache[$this->currentPackage->getName()] = array(
            'md5' => md5(file_get_contents($this->destinationMin)),
            'max' => $this->currentPackage->getTarget() . '/' . $this->currentPackage->getFilenameMaxFile(),
            'min' => $this->currentPackage->getTarget() . '/' . $this->currentPackage->getFilenameMinFile()
        );
    }

    /**
     * @return void
     */
    public function postCommand() {
        file_put_contents($this->cacheFilename, '<?php return ' . var_export($this->cache, true) . ";" . PHP_EOL);

        $this->output->writeln("");
        $this->writeInfo("created file: {$this->cacheFilename}");
        $this->output->writeln("");
    }

    /**
     * @return void
     */
    abstract public function compress();
}