<?php

namespace Bootstrap\Test\TestCase\View\Widget;

use Bootstrap\View\Widget\BasicWidget;
use Cake\View\StringTemplate;
use PHPUnit\Framework\TestCase;

/**
 * Class BasicWidgetTest
 *
 * @package Bootstrap\Test\TestCase\View\Widget
 */
class BasicWidgetTest extends TestCase
{

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        parent::setUp();
        $templates = [
            'input' => '<input type="{{type}}" name="{{name}}"{{attrs}}>',
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
        $text = new BasicWidget($this->templates);
        $result = $text->render(['name' => 'my_input'], $this->context);

        $expected = '<input type="text" name="my_input" class="form-control">';
        $this->assertEquals($expected, $result);
    }

    /**
     * Test render with value.
     *
     * @return void
     */
    public function testRenderWithValue()
    {
        $text = new BasicWidget($this->templates);
        $result = $text->render([
            'name' => 'my_input',
            'val' => 'foo',
        ], $this->context);

        $expected = '<input type="text" name="my_input" class="form-control" value="foo">';
        $this->assertEquals($expected, $result);
    }

    /**
     * Test render with attributes.
     *
     * @return void
     */
    public function testRenderWithAttributes()
    {
        $text = new BasicWidget($this->templates);
        $result = $text->render([
            'name' => 'my_input',
            'class' => 'danger',
            'required' => true,
        ], $this->context);

        $expected = '<input type="text" name="my_input" class="form-control danger" required="required">';
        $this->assertEquals($expected, $result);
    }
}
