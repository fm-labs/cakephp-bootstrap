<?php
declare(strict_types=1);

namespace Bootstrap\View\Helper;

/**
 * Class ContentBlockHelperTrait
 *
 * @package Backend\View
 * @property \Cake\View\View $_View
 */
trait ContentBlockHelperTrait
{
    /**
     * @var string
     */
    protected $_blockActive;

    /**
     * @var string
     */
    protected $_blockId;

    /**
     * @var array
     */
    protected $_blocks = [];

    /**
     * Start content block
     *
     * @param string $block
     */
    public function start($block = 'body')
    {
        //debug("Start ContentBlock " . $block);
        if ($this->_blockActive) {
            //debug("Attempting to start block $block, while block is active: " . $this->_blockActive);
            $this->end();
        }

        $namespace = $this->_blockNamespace ?? uniqid('block');

        $this->_blockId = $namespace . '-' . $block;
        $this->_View->start($this->_blockId);
        $this->_blockActive = $block;
    }

    /**
     * End active block
     *
     * @return null
     */
    public function end()
    {
        if (!$this->_blockActive) {
            return null;
        }

        //debug("End ContentBlock " . $this->_blockActive);
        $this->_View->end();
        $content = $this->_blocks[$this->_blockActive] = $this->_View->fetch($this->_blockId);
        $this->_View->assign($this->_blockId, null);
        $this->_blockActive = null;
        $this->_blockId = null;

        return $content;
    }

    /**
     * Clean all content blocks
     */
    public function clean()
    {
        $this->end();
        $this->_blocks = [];
    }

    /**
     * @param $block
     * @return null
     */
    public function getContent($block)
    {
        if (isset($this->_blocks[$block])) {
            return $this->_blocks[$block];
        }

        return null;
    }
}
