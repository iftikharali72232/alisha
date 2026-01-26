<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\Component;

class SmartImage extends Component
{
    public string $src;
    public string $alt;
    public string $class;
    public string $placeholder;

    /**
     * Create a new component instance.
     */
    public function __construct(
        ?string $src = null,
        string $alt = '',
        string $class = '',
        string $type = 'default'
    ) {
        $this->alt = $alt;
        $this->class = $class;
        
        // Generate placeholder based on type
        $placeholders = [
            'featured' => 'https://via.placeholder.com/800x500/f43f5e/ffffff?text=Featured+Image',
            'thumbnail' => 'https://via.placeholder.com/400x300/f43f5e/ffffff?text=Thumbnail',
            'avatar' => 'https://ui-avatars.com/api/?name=' . urlencode($alt) . '&background=f43f5e&color=fff&size=200',
            'slider' => 'https://via.placeholder.com/1920x600/1f2937/ffffff?text=Slider+Image',
            'gallery' => 'https://via.placeholder.com/800x600/f43f5e/ffffff?text=Gallery+Image',
            'default' => 'https://via.placeholder.com/800x600/f43f5e/ffffff?text=Image',
        ];
        
        $this->placeholder = $placeholders[$type] ?? $placeholders['default'];
        
        if (empty($src)) {
            $this->src = $this->placeholder;
        } elseif (Str::startsWith($src, ['http://', 'https://'])) {
            $this->src = $src;
        } else {
            $this->src = Storage::url($src);
        }
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.smart-image');
    }
}
