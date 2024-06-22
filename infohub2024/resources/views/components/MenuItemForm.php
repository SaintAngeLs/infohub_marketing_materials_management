<?php

namespace App\View\Components;

use Illuminate\View\Component;

class MenuItemForm extends Component
{
    public $menuItem;
    public $menuItemsToSelect;

    public function __construct($menuItem = null, $menuItemsToSelect)
    {
        $this->menuItem = $menuItem;
        $this->menuItemsToSelect = $menuItemsToSelect;
    }

    public function render()
    {
        return view('components.menu-form-component.menu-form-component');
    }
}
