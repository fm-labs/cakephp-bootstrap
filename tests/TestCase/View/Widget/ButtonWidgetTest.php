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
     * @var StringTemplate
     */
    public $templates;

    /**
     * @var \PHPUnit_Framework_MockObject_MockObject
     */
    public $context;

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
     * @return void
     */
    public function testRender()
    {
        $button = new ButtonWidget($this->templates);
        $result = $button->render([], $this->context);

        $expected = '<button class="btn"></button>';
        $this->assertEquals($expected, $result);
    }

    /**
     * Test render submit button.
     * @return void
     */
    public function testRenderSubmit()
    {
        $button = new ButtonWidget($this->templates);
        $result = $button->render(['type' => 'submit'], $this->context);

        $expected = '<button type="submit" class="btn btn-primary"></button>';
        $this->assertEquals($expected, $result);
    }

    /**
     * Test render with button text.
     * @return void
     */
    public function testRenderWithText()
    {
        $button = new ButtonWidget($this->templates);
        $result = $button->render(['text' => 'Test'], $this->context);

        $expected = '<button class="btn">Test</button>';
        $this->assertEquals($expected, $result);
    }

    /**
     * Test render with button text.
     * @return void
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
     * @return void
     */
    public function testRenderWithAttributes()
    {
        $button = new ButtonWidget($this->templates);
        $result = $button->render(['text' => 'Test', 'data-test' => 'foo'], $this->context);

        $expected = '<button data-test="foo" class="btn">Test</button>';
        $this->assertEquals($expected, $result);
    }

    /**
     * Test render default button.
     * @return void
     */
    public function testRenderWithBuiltInClassAttributes()
    {
        $button = new ButtonWidget($this->templates);

        $result = $button->render(['class' => 'success'], $this->context);
        $expected = '<button class="btn btn-success"></button>';
        $this->assertEquals($expected, $result);

        $result = $button->render(['class' => 'default'], $this->context);
        $expected = '<button class="btn btn-default"></button>';
        $this->assertEquals($expected, $result);

        $result = $button->render(['class' => 'danger'], $this->context);
        $expected = '<button class="btn btn-danger"></button>';
        $this->assertEquals($expected, $result);

        $result = $button->render(['class' => 'warning'], $this->context);
        $expected = '<button class="btn btn-warning"></button>';
        $this->assertEquals($expected, $result);

        $result = $button->render(['class' => 'info'], $this->context);
        $expected = '<button class="btn btn-info"></button>';
        $this->assertEquals($expected, $result);

        $result = $button->render(['class' => 'link'], $this->context);
        $expected = '<button class="btn btn-link"></button>';
        $this->assertEquals($expected, $result);

        $result = $button->render(['class' => 'foo'], $this->context);
        $expected = '<button class="btn foo"></button>';
        $this->assertEquals($expected, $result);
    }
}
