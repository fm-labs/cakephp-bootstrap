<?php

namespace Bootstrap\Test\TestCase\View\Widget;

use Bootstrap\View\Widget\ButtonWidget;
use Cake\TestSuite\TestCase;
use Cake\View\StringTemplate;

/**
 * Class ButtonWidgetTest
 *
 * @package Bootstrap\Test\TestCase\View\Widget
 */
class ButtonWidgetTest extends TestCase
{

    /**
     * {@inheritDoc}
     */
    public function setUp()
    {
        parent::setUp();
        $templates = [
            'button' => '<button{{attrs}}>{{text}}</button>',
        ];
        $this->templates = new StringTemplate($templates);
        $this->context = $this->getMockBuilder('Cake\View\Form\ContextInterface')->getMock();
    }

    /**
     * Test render default button.
     */
    public function testRender()
    {
        $button = new ButtonWidget($this->templates);
        $result = $button->render([], $this->context);

        $expected = '<button class="btn" type="submit"></button>';
        $this->assertEquals($expected, $result);
    }

    /**
     * Test render with button text.
     */
    public function testRenderWithText()
    {
        $button = new ButtonWidget($this->templates);
        $result = $button->render(['text' => 'Test'], $this->context);

        $expected = '<button class="btn" type="submit">Test</button>';
        $this->assertEquals($expected, $result);
    }

    /**
     * Test render with button text.
     */
    public function testRenderType()
    {
        $button = new ButtonWidget($this->templates);
        $result = $button->render(['text' => 'Reset', 'type' => 'reset'], $this->context);

        $expected = '<button type="reset" class="btn">Reset</button>';
        $this->assertEquals($expected, $result);
    }

    /**
     * Test render with button text.
     */
    public function testRenderWithAttributes()
    {
        $button = new ButtonWidget($this->templates);
        $result = $button->render(['text' => 'Test', 'data-test' => 'foo'], $this->context);

        $expected = '<button data-test="foo" class="btn" type="submit">Test</button>';
        $this->assertEquals($expected, $result);
    }

    /**
     * Test render default button.
     */
    public function testRenderWithBuiltInClassAttributes()
    {
        $button = new ButtonWidget($this->templates);

        $result = $button->render(['class' => 'success'], $this->context);
        $expected = '<button class="btn btn-success" type="submit"></button>';
        $this->assertEquals($expected, $result);

        $result = $button->render(['class' => 'default'], $this->context);
        $expected = '<button class="btn btn-default" type="submit"></button>';
        $this->assertEquals($expected, $result);

        $result = $button->render(['class' => 'danger'], $this->context);
        $expected = '<button class="btn btn-danger" type="submit"></button>';
        $this->assertEquals($expected, $result);

        $result = $button->render(['class' => 'warning'], $this->context);
        $expected = '<button class="btn btn-warning" type="submit"></button>';
        $this->assertEquals($expected, $result);

        $result = $button->render(['class' => 'info'], $this->context);
        $expected = '<button class="btn btn-info" type="submit"></button>';
        $this->assertEquals($expected, $result);

        $result = $button->render(['class' => 'link'], $this->context);
        $expected = '<button class="btn btn-link" type="submit"></button>';
        $this->assertEquals($expected, $result);

        $result = $button->render(['class' => 'foo'], $this->context);
        $expected = '<button class="btn foo" type="submit"></button>';
        $this->assertEquals($expected, $result);
    }
}
