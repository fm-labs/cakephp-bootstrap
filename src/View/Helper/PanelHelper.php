<?php
namespace Bootstrap\View\Helper;

use Cake\View\Helper;
use Cake\View\StringTemplateTrait;

/**
 * Class PanelHelper
 *
 * @package Bootstrap\View\Helper
 */
class PanelHelper extends Helper
{
    use StringTemplateTrait;

    /**
     * @var array
     */
    public $helpers = ['Html'];

    /**
     * Default config for this class
     *
     * @var array
     */
    protected $_defaultConfig = [
        'templates' => [
            'panel' => '<div class="panel panel-default {{class}}" {{attrs}}>{{heading}}{{body}}{{footer}}</div>',
            'panelHeading' => '<div class="panel-heading">{{content}}</div>',
            'panelBody' => '<div class="panel-body">{{content}}</div>',
            'panelFooter' => '<div class="panel-footer">{{actions}}</div>',
            //'panelActionList' => '<div class="actions btn-group"><ul><li>{{items}}</li></ul>',
            //'panelActionListItem' => '<li>{{link}}</li>',
            'panelActionList' => '<div class="">{{items}}</div>',
            'panelActionListItem' => '{{link}}',

        ],
    ];

    /**
     * @var array
     */
    protected $_defaultParams = [
        'id' => null,
        'title' => null,
        'class' => 'panel-primary',
    ];

    /**
     * @var string
     */
    protected $_id;

    /**
     * @var array
     */
    protected $_params = [];

    /**
     * @var string
     */
    protected $_currentBlock;

    /**
     * @var array
     */
    protected $_contents = [ 'heading' => '', 'body' => [], 'footer' => ''];

    /**
     * @var array
     */
    protected $_actions = [];

    /**
     * @param null $title
     * @param array $params
     */
    public function create($title = null, $params = [])
    {
        $this->clean();

        if (is_array($title)) {
            $params = $title;
            $title = null;
        }

        $params['title'] = $title;

        $this->_params = $params + $this->_defaultParams;
        $this->_id = ($this->_params['id']) ?: uniqid('panel');
    }

    /**
     * @param null $content
     */
    public function heading($content = null)
    {
        if ($content === null) {
            $this->start('heading');

            return;
        }

        $this->_contents['heading'] = $content;
    }

    /**
     * @param null $content
     * @param bool|true $merge
     */
    public function body($content = null, $merge = true)
    {
        if ($merge !== true) {
            $this->_contents['body'] = [];
        }

        if ($content === null) {
            $this->start('body');

            return;
        }

        $this->_contents['body'][] = $content;
    }

    /**
     * @param string $block
     */
    public function start($block = 'body')
    {
        if ($this->_currentBlock) {
            //debug("Attempting to start block $block, while block is active: " . $this->_currentBlock);
            $this->end();
        }

        $blockId = 'panel-' . $block . $this->_id;
        $this->_View->Blocks->start($blockId);
        $this->_currentBlock = $block;
    }

    /**
     *
     */
    public function end()
    {
        if (!$this->_currentBlock) {
            return;
        }

        $blockId = 'panel-' . $this->_currentBlock . $this->_id;

        $this->_View->Blocks->end();
        $content = $this->_View->Blocks->get($blockId);
        if ($this->_currentBlock === 'body') {
            $this->_contents[$this->_currentBlock][] = $content;
        } else {
            $this->_contents[$this->_currentBlock] = $content;
        }

        $this->_View->assign($blockId, null);
        $this->_currentBlock = null;
    }

    /**
     * @param $title
     * @param $url
     * @param array $attrs
     */
    public function addAction($title, $url, $attrs = [])
    {
        $this->_actions[] = compact('title', 'url', 'attrs');
    }

    /**
     * @return null|string
     */
    public function renderHeading()
    {
        $content = $this->_contents['heading'];

        if (!$content && $this->_params['title']) {
            $content = h($this->_params['title']);
        }

        if (!$content) {
            return '';
        }

        return $this->templater()->format('panelHeading', [
            'content' => $content,
        ]);
    }

    /**
     * @return string
     */
    public function renderBody()
    {
        $out = "";

        foreach ($this->_contents['body'] as $content) {
            $out .= $this->templater()->format('panelBody', [
                'content' => $content,
            ]);
        }

        return $out;
    }

    /**
     * @return null|string
     */
    public function renderFooter()
    {
        /*
        $content = $this->_contents['footer'];
        if (!$content) {
            return '';
        }
        */
        if (empty($this->_actions)) {
            return '';
        }

        return $this->templater()->format('panelFooter', [
            'actions' => $this->renderActions(),
        ]);
    }

    /**
     * @return null|string
     */
    public function renderActions()
    {
        $items = "";

        foreach ($this->_actions as $action) {
            $items .= $this->templater()->format('panelActionListItem', [
                'link' => $this->Html->link($action['title'], $action['url'], $action['attrs']),
            ]);
        }

        $out = $this->templater()->format('panelActionList', [
            'items' => $items,
        ]);

        return $out;
    }

    /**
     * @return null|string
     */
    public function render()
    {
        $this->end();

        $html = $this->templater()->format('panel', [
            'heading' => $this->renderHeading(),
            'body' => $this->renderBody(),
            'footer' => $this->renderFooter(),
            'class' => $this->_params['class'],
            'attrs' => $this->templater()->formatAttributes($this->_params, ['class']),
        ]);

        $this->clean();

        return $html;
    }

    /**
     * Clean
     */
    public function clean()
    {
        $this->end();
        $this->_id = null;
        $this->_params = [];
        $this->_currentBlock = null;
        $this->_contents = [ 'heading' => '', 'body' => [], 'footer' => ''];
        $this->_actions = [];
    }
}
