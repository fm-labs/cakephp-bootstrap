<?php

namespace Bootstrap\Test\TestCase\View\Widget;

use Bootstrap\View\Widget\HiddenWidget;
use Cake\View\StringTemplate;
use PHPUnit\Framework\TestCase;

/**
 * Class BasicWidgetTest
 *
 * @package Bootstrap\Test\TestCase\View\Widget
 */
class HiddenWidgetTest extends TestCase
{
    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        parent::setUp();
        $templates = [
            'inputHidden' => '<input type="hidden" name="{{name}}"{{attrs}} />',
        ];
        $this->templates = new StringTemplate($templates);
        $this->context = $this->getMockBuilder('Cake\View\Form\ContextInterface')->getMock();
    }

    /**
     * Test render in a simple case.
     *
     * @return void
     */
    public function testRenderSimple()
    {
        $text = new HiddenWidget($this->templates);
        $result = $text->render(['name' => 'my_input'], $this->context);

        $expected = '<input type="hidden" name="my_input" class="form-control" />';
        $this->assertEquals($expected, $result);
    }
}
