<?php

namespace Day4\TreeView;

use Laravel\Nova\Nova;
use Laravel\Nova\Tool;

class TreeView extends Tool
{
    protected $trees = [];

    function __construct($trees) {
        $this->trees = $trees;
        parent::__construct();
    }

    /**
     * Perform any tasks that need to happen when the tool is booted.
     *
     * @return void
     */
    public function boot()
    {
        Nova::script('tree-view', __DIR__.'/../dist/js/tool.js');
        Nova::style('tree-view', __DIR__.'/../dist/css/tool.css');
    }

    /**
     * Build the view that renders the navigation links for the tool.
     *
     * @return \Illuminate\View\View
     */
    public function renderNavigation()
    {
        return view('tree-view::navigation', [
            'trees' => $this->trees
        ]);
    }
}
