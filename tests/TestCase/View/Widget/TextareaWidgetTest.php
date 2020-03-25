<?php
declare(strict_types=1);

namespace Bootstrap\Test\TestCase\View\Widget;

use Bootstrap\View\Widget\TextareaWidget;
use Cake\View\StringTemplate;
use PHPUnit\Framework\TestCase;

/**
 * Class BasicWidgetTest
 *
 * @package Bootstrap\Test\TestCase\View\Widget
 */
class TextareaWidgetTest extends TestCase
{
    /**
     * {@inheritDoc}
     */
    public function setUp(): void
    {
        parent::setUp();
        $templates = [
            'textarea' => '<textarea name="{{name}}"{{attrs}}>{{value}}</textarea>',
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
        $text = new TextareaWidget($this->templates);
        $result = $text->render(['name' => 'my_input'], $this->context);

        $expected = '<textarea name="my_input" rows="5" class="form-control"></textarea>';
        $this->assertEquals($expected, $result);
    }

    /**
     * Test render with text.
     *
     * @return void
     */
    public function testRenderWithText()
    {
        $text = new TextareaWidget($this->templates);
        $result = $text->render(['name' => 'my_input', 'val' => 'Foo Bar'], $this->context);

        $expected = '<textarea name="my_input" rows="5" class="form-control">Foo Bar</textarea>';
        $this->assertEquals($expected, $result);
    }
}
