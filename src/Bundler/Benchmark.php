<?php
namespace Bundler;

/**
 * Class Benchmark
 *
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class Benchmark {

    /**
     * @var int
     */
    private $timeStart;

    /**
     * @var int
     */
    private $timeEnd;

    /**
     * @return void
     */
    public function start() {
        $this->timeStart = microtime(true);
        $this->timeEnd = 0;
    }

    /**
     * @return void
     */
    public function stop() {
        $this->timeEnd = microtime(true);
    }

    /**
     * @return int
     */
    public function getTime() {
        return $this->timeEnd - $this->timeStart;
    }
}