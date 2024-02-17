<?php

namespace KamilSawicki\LaravelFormBuilder\Types;

use KamilSawicki\LaravelFormBuilder\Types\AbstractInputType;

class TextType extends AbstractInputType
{
    protected function getType(): string
    {
        return 'text';
    }
}
