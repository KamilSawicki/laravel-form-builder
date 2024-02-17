<?php

namespace KamilSawicki\LaravelFormBuilder\Types;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;
use KamilSawicki\LaravelFormBuilder\TypeInterface;

abstract class AbstractInputType implements TypeInterface
{
    public function __construct(
        protected string $name,
        protected mixed $label = null,
        protected mixed $value = null,
        protected ?string $class = null,
        protected ?string $id = null,
        protected array|Collection|null $attr = null,
        protected ?string $wrapperClass = null,
    ) {
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function renderControl(): string
    {
        $attributes = new Collection([
            'name' => $this->name,
            'value' => $this->value,
            'id' => $this->id ?? $this->name,
            'class' => $this->class,
        ]);

        $attributesString = $attributes
            ->merge($this->attr)
            ->filter()
            ->map(fn ($v, $k) => "$k=\"$v\"")
            ->implode(' ');

        return Blade::render('<input {!! $attr !!}>', ['attr' => $attributesString]);
    }

    public function renderLaber(): string
    {
        return Blade::render('<label for="{{ $target }}">{{ $label }}</label>', [
            'target' => $this->id ?? $this->name,
            'label' => $this->label,
        ]);
    }

    public function render(): string
    {
        return Blade::render('<div @if($class)class="{{ $class }}"@endif>{!! $label !!}{!! $control !!}</div>', [
            'label' => $this->renderLaber(),
            'control' => $this->renderControl(),
            'class' => $this->wrapperClass,
        ]);
    }

    abstract protected function getType(): string;
}
