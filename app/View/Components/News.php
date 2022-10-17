<?php

namespace App\View\Components;

use Illuminate\View\Component;

class News extends Component
{
    public string $newTitle;
    public string $newSlug;
    public string $newImage;

    public function __construct($new)
    {
        $this->newTitle = $new->title;
        $this->newSlug = $new->slug;
        $this->newImage = $new->image_thumb;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.news');
    }
}
