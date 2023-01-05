<?php
declare(strict_types=1);

namespace Bootstrap\Test\TestCase\View\Helper;

use Bootstrap\View\Helper\NavbarHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;

/**
 * Bootstrap\View\Helper\NavbarHelper Test Case
 */
class NavbarHelperTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \Bootstrap\View\Helper\NavbarHelper
     */
    protected $Navbar;

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $view = new View();
        $this->Navbar = new NavbarHelper($view);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Navbar);

        parent::tearDown();
    }
}
