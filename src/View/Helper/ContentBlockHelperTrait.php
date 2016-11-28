<?php

namespace Bootstrap\View\Helper;
use Cake\View\View;
use Cake\View\ViewBlock;

/**
 * Class ContentBlockHelperTrait
 * @package Backend\View
 * @property View $_View
 */
trait ContentBlockHelperTrait
{

    protected $_blockActive;
    protected $_blockId;
    protected $_blocks = [];

    public function start($block = 'body', $mode = ViewBlock::OVERRIDE)
    {
        //debug("Start ContentBlock " . $block);
        if ($this->_blockActive) {
            //debug("Attempting to start block $block, while block is active: " . $this->_blockActive);
            $this->end();
        }

        $namespace = (isset($this->_blockNamespace)) ? $this->_blockNamespace : uniqid('block');

        $this->_blockId = $namespace . '-' . $block;
        $this->_View->Blocks->start($this->_blockId, $mode);
        $this->_blockActive = $block;
    }

    public function end()
    {
        if (!$this->_blockActive) {
            return null;
        }

        //debug("End ContentBlock " . $this->_blockActive);
        $this->_View->Blocks->end();
        $content = $this->_blocks[$this->_blockActive] = $this->_View->Blocks->get($this->_blockId);
        $this->_View->Blocks->set($this->_blockId, null);
        $this->_blockActive = null;
        $this->_blockId = null;
        return $content;
    }

    public function clean() {
        $this->end();
        $this->_blocks = [];
    }

    public function getContent($block) {
        if (isset($this->_blocks[$block])) {
            return $this->_blocks[$block];
        }
        return null;
    }
}