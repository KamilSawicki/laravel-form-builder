<?php

namespace KamilSawicki\LaravelFormBuilder;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Blade;

abstract class AbstractFormBuilder
{
    protected ?Collection $fields = null;
    protected ?Collection $attr = null;

    public function __construct()
    {
        $this->fields = new Collection();
        $this->attr = new Collection(['method' => 'POST', 'action' => request()->path()]);

        $this->build();
    }

    public function get(string $name): ?TypeInterface
    {
        return $this->fields->get($name);
    }

    public function toCollection(): Collection
    {
        return $this->fields->map(fn (TypeInterface $field) => $field->getValue());
    }

    public function toArray(): array
    {
        return $this->toCollection()->toArray();
    }

    public function renderStart(): string
    {
        $attrString = $this->attr
            ->map(fn ($v, $k) => "$k=\"$v\"")
            ->values()
            ->implode(' ');

        return Blade::render('<form {!! $attr !!}>', ['attr' => $attrString]);
    }

    public function renderEnd(): string
    {
        return '</form>';
    }

    public function render(): string
    {
        return (new Collection([
            $this->renderStart(),
            ...$this->fields->map(fn (TypeInterface $i) => $i->render())->values(),
            $this->renderEnd(),
        ]))->implode(PHP_EOL);
    }

    protected function add(TypeInterface $type): self
    {
        if (is_null($this->fields)) {
            $this->fields = new Collection();
        }

        $this->fields->put($type->getName(), $type);

        return $this;
    }

    protected function addAttr(string $name, string $value): self
    {
        $this->attr->put($name, $value);

        return $this;
    }

    abstract protected function build(): void;
}
