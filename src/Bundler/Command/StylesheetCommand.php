<?php
namespace Bundler\Command;

use Exception;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class StylesheetCommand
 *
 * @package Bundler\Command
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class StylesheetCommand extends AbstractPublicCommand {

    /**
     * @return void
     */
    protected function configure() {
        $this->manifest = "stylesheet.yaml";
        $this->compiler = "yuicompressor";
        $this->compilers = array(
            "yuicompressor",
            "cssmin"
        );

        parent::configure();

        $this->setName('bundle:stylesheet');
        $this->setDescription('bundling stylesheet');
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return void
     */
    protected function execute(InputInterface $input, OutputInterface $output) {
        $output->writeln("<comment>bundling stylesheet</comment>");

        parent::execute($input, $output);

        $this->bundle();
        $this->output->writeln("");
    }

    /**
     * @return void
     * @throws Exception
     */
    protected function bundle() {
        parent::bundle();

        foreach($this->filesSelected as $this->currentPackage => $this->filesSelectedByPackage) {
            $this->outputBundlingPackage();

            $this->content = array();
            $this->destinationMax = "{$this->target}/{$this->currentPackage}.bundler.css";
            $this->destinationMin = "{$this->target}/{$this->currentPackage}.bundler.min.css";

            foreach($this->filesSelectedByPackage['files'] as $file) {
                $this->output->writeln("  <info>include: {$file}</info>");

                $path = pathinfo($file);
                $path = $this->getRelativePath($path['dirname'], $this->target);

                $css = file_get_contents($file);
                $css = $this->changeUrlPath($path, $css);

                $this->content[] = $css;
            }

            // create max file
            $this->content = implode(PHP_EOL . PHP_EOL, $this->content);
            file_put_contents($this->destinationMax, $this->content);

            switch($this->compiler) {
                case "yuicompressor":
                    $this->compileWithYuiCompressor();
                    $this->output->writeln("");
                    $this->output->writeln("  <info>compiled by yuicompressor</info>");
                    break;

                case "cssmin":
                    $this->compileWithCSSMin();
                    $this->output->writeln("");
                    $this->output->writeln("  <info>compiled by cssmin</info>");
                    break;
            }

            $this->outputBundlingFilesByPackage();
            $this->outputBundlingFilesCompression();

            // create php loader file
            $pathMax = basename($this->target) . "/{$this->currentPackage}.bundler.css";
            $pathMin = basename($this->target) . "/{$this->currentPackage}.bundler.min.css";

            $paths = array(
                'max' => "{$pathMax}?v=" . md5_file($this->destinationMax),
                'min' => "{$pathMin}?v=" . md5_file($this->destinationMin),
            );

            file_put_contents("{$this->target}/{$this->currentPackage}.bundler.php", "<?php return " . var_export($paths, true) . ";");
        }
    }

    /**
     * @param string $path
     * @param string $from
     * @return string
     */
    private function getRelativePath($path, $from) {
        $path = explode(DIRECTORY_SEPARATOR, $path);
        $from = rtrim($from, '/') . '/';
        $from = explode(DIRECTORY_SEPARATOR, dirname($from . '.'));
        $common = array_intersect_assoc($path, $from);

        $base = array('.');

        if($pre_fill = count(array_diff_assoc($from, $common))) {
            $base = array_fill(0, $pre_fill, '..');
        }

        $path = array_merge($base, array_diff_assoc($path, $common));

        return implode(DIRECTORY_SEPARATOR, $path);
    }

    /**
     * @param $baseUrl
     * @param $content
     * @return string
     */
    private function changeUrlPath($baseUrl, $content) {
        return preg_replace('/url\(\s*[\'"]?\/?(.+?)[\'"]?\s*\)/i', 'url(' . $baseUrl . '/$1)', $content);
    }

    /**
     * @return void
     * @throws Exception
     */
    protected function compileWithYuiCompressor() {
        $command = $this->thirdParty . "/../bin/yui-compressor --type css --line-break 5000 -o {$this->destinationMin} {$this->destinationMax}";
        exec($command);
    }

    /**
     * @return void
     */
    protected function compileWithCSSMin() {
        require_once $this->thirdParty . '/cssmin/3.0.1/source/CssMin.php';

        $cssFilter = array(
            'ImportImports' => false,
            // default false
            'ConvertLevel3Properties' => true
            // default true
        );

        $cssPlugins = array(
            'Variables' => true,
            // default true
            'ConvertFontWeight' => true,
            // default false
            'ConvertNamedColors' => true,
            // default false
            'CompressColorValues' => true,
            // default false
            'CompressUnitValues' => false
            // default false
        );

        $minifier = new CssMinifier($this->content, $cssFilter, $cssPlugins, false);

        file_put_contents($this->destinationMin, $minifier->getMinified());
    }
}