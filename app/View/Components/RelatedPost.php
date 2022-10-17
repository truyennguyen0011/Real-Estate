<?php

namespace App\View\Components;

use Illuminate\View\Component;

class RelatedPost extends Component
{
    public string $postTitle;
    public string $postSlug;
    public string $postPrice;
    public string $postArea;
    public string $postAddress;
    public $postImage;

    public function __construct($post)
    {
        $this->postTitle = $post->title;
        $this->postSlug = $post->slug;
        $this->postPrice = $post->price;
        $this->postArea = $post->area;
        $this->postAddress = $post->address;

        if (count($post->images) > 0) {
            $this->postImage = $post->images[0];
        }
    }
    
    public function render()
    {
        return view('components.related-post');
    }
}
