<?php
/**
 * Created by PhpStorm.
 * User: flow
 * Date: 10/16/16
 * Time: 2:29 PM
 */

namespace Bootstrap\View\Helper;


use Cake\View\Helper;
use Cake\View\StringTemplateTrait;

class PanelHelper extends Helper
{
    public $helpers = ['Html'];

    use StringTemplateTrait;

    /**
     * Default config for this class
     *
     * @var array
     */
    protected $_defaultConfig = [
        'templates' => [
            'panel' => '<div class="panel panel-default panel-primary">{{heading}}{{body}}{{footer}}</div>',
            'panelHeading' => '<div class="panel-heading">{{content}}</div>',
            'panelBody' => '<div class="panel-body">{{content}}</div>',
            'panelFooter' => '<div class="panel-footer">{{actions}}</div>',
            //'panelActionList' => '<div class="actions btn-group"><ul><li>{{items}}</li></ul>',
            //'panelActionListItem' => '<li>{{link}}</li>',
            'panelActionList' => '<div class="btn-group">{{items}}</div>',
            'panelActionListItem' => '{{link}}',

        ],
    ];

    protected $_defaultParams = [
        'id' => null,
        'title' => null
    ];

    protected $_id;
    protected $_params = [];

    protected $_currentBlock;
    protected $_contents = [ 'heading' => '', 'body' => [], 'footer' => ''];
    protected $_actions = [];

    public function create($title = null, $params = []) {

        if (is_array($title)) {
            $params = $title;
            $title = null;
        }

        $params['title'] = $title;

        $this->_params = $params += $this->_defaultParams;
        $this->_id = ($this->_params['id']) ?: uniqid('panel');
    }

    public function heading($content = null) {
        if ($content === null) {
            $this->start('heading');
            return;
        }

        $this->_contents['heading'] = $content;
    }

    public function body($content = null, $merge = true) {

        if ($merge !== true) {
            $this->_contents['body'] = [];
        }

        if ($content === null) {
            $this->start('body');
            return;
        }

        $this->_contents['body'][] = $content;
    }

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

        $this->_View->Blocks->set($blockId, null);
        $this->_currentBlock = null;
    }

    public function addAction($title, $url, $attrs = []) {
        $this->_actions[] = compact('title', 'url', 'attrs');
    }

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
            'content' => $content
        ]);
    }

    public function renderBody()
    {
        $out = "";

        foreach ($this->_contents['body'] as $content) {
            $out .= $this->templater()->format('panelBody', [
                'content' => $content
            ]);
        }

        return $out;
    }

    public function renderFooter()
    {
        /*
        $content = $this->_contents['footer'];
        if (!$content) {
            return '';
        }
        */
        return $this->templater()->format('panelFooter', [
            'actions' => $this->renderActions()
        ]);
    }

    public function renderActions()
    {
        $items = "";

        foreach ($this->_actions as $action) {
            $items .= $this->templater()->format('panelActionListItem', [
                'link' => $this->Html->link($action['title'], $action['url'], $action['attrs'])
            ]);
        }

        $out = $this->templater()->format('panelActionList', [
            'items' => $items
        ]);

        return $out;
    }

    public function render()
    {
        $this->end();

        return $this->templater()->format('panel', [
            'heading' => $this->renderHeading(),
            'body' => $this->renderBody(),
            'footer' => $this->renderFooter()
        ]);
    }
}