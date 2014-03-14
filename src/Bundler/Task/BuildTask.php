<?php
namespace Bundler\Task;

use Exception;

/**
 * Class BuildTask
 *
 * @package Bundler\Task
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class BuildTask extends AbstractPublicTask {

    /**
     * @var string
     */
    protected $_target;

    /**
     * @return void
     * @throws Exception
     */
    public function bundle() {
        parent::bundle();

        $this->_target = "{$this->_root}/{$this->_manifestDefinition['target']}";

        if($this->_target === false) {
            throw new Exception("Target {$this->_target} not found.");
        }

        foreach($this->_filesSelected as $package => $data) {
            $this->_output->writeln("");
            $this->_output->writeln("<comment>package: {$package}</comment>");

            foreach($data['files'] as $file) {
                $this->_output->writeln("  <info>copy: {$file}</info>");

                $destination = $this->_target . '/' . $package . '/' . str_replace($this->_folder . '/', '', $file);
                $directory = dirname($destination);

                if(!file_exists($directory)) {
                    if(!mkdir($directory, 0755, true)) {
                        throw new Exception("Unable to create {$directory}");
                    }
                }

                if(!copy($file, $destination)) {
                    throw new Exception("Unable to create {$destination}");
                }
            }

            $this->_output->writeln("");
            $this->_output->writeln("<comment>package: {$package}</comment>");
            $this->_output->writeln("  <info>include: " . count($data['includes']) . "</info>");
            $this->_output->writeln("  <info>exclude: " . count($data['excludes']) . "</info>");
            $this->_output->writeln("  <info>copy:    " . count($data['files']) . "</info>");
        }
    }
}