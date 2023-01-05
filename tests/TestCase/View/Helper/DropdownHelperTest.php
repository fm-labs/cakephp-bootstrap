<?php
declare(strict_types=1);

namespace Bootstrap\Test\TestCase\View\Helper;

use Bootstrap\View\Helper\DropdownHelper;
use Cake\TestSuite\TestCase;
use Cake\View\View;

/**
 * Bootstrap\View\Helper\DropdownHelper Test Case
 */
class DropdownHelperTest extends TestCase
{
    /**
     * Test subject
     *
     * @var \Bootstrap\View\Helper\DropdownHelper
     */
    protected $Dropdown;

    /**
     * setUp method
     *
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();
        $view = new View();
        $this->Dropdown = new DropdownHelper($view);
    }

    /**
     * tearDown method
     *
     * @return void
     */
    protected function tearDown(): void
    {
        unset($this->Dropdown);

        parent::tearDown();
    }
}
