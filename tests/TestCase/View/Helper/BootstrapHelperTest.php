<?php
declare(strict_types=1);

namespace Bootstrap\Test\TestCase\View\Helper;

use Bootstrap\View\Helper\BootstrapHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;

/**
 * Bootstrap\View\Helper\BootstrapHelper Test Case
 */
class BootstrapHelperTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \Bootstrap\View\Helper\BootstrapHelper
     */
    public $Bootstrap;

    /**
     * @var View
     */
    public $view;

    /**
     * setUp method
     *
     * @return void
     */
    public function setUp(): void
    {
        parent::setUp();
        $this->view = new View();
        $this->Bootstrap = new BootstrapHelper($this->view);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    public function tearDown(): void
    {
        unset($this->Bootstrap);

        parent::tearDown();
    }

    /**
     * Test initial setup
     *
     * @return void
     */
    public function testInitialization()
    {
        $this->assertTextContains('bootstrap.min.css', $this->view->fetch('css'));
        $this->assertTextContains('bootstrap.min.js', $this->view->fetch('script'));
    }
}
