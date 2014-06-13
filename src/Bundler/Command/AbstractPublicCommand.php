<?php
namespace Bundler\Command;

/**
 * Class AbstractPublicCommand
 *
 * @package Bundler\Command
 * @author Jeff Tunessen <jeff.tunessen@gmail.com>
 */
class AbstractPublicCommand extends AbstractCommand {

    /**
     * @var string
     */
    protected $compiler;

    /**
     * @var array
     */
    protected $compilers;

    /**
     * @var string
     */
    protected $content;

    /**
     * @var string
     */
    protected $destinationMax;

    /**
     * @var string
     */
    protected $destinationMin;
}