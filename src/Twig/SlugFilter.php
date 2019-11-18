<?php

namespace App\Twig;

use App\Helper\SlugHelper;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class SlugFilter extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('slug', [$this, 'formatSlug']),
        ];
    }

    public function formatSlug($string): string
    {
       return SlugHelper::slugify($string);
    }
}
