<?php

namespace KamilSawicki\LaravelFormBuilder;

use Illuminate\Support\Collection;

abstract class AbstractFormBuilder
{
    protected ?Collection $fields = null;

    public function __construct()
    {
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

    protected function push(TypeInterface $type): self
    {
        if (is_null($this->fields)) {
            $this->fields = new Collection();
        }

        $this->fields->put($type->getName(), $type);

        return $this;
    }

    abstract protected function build(): void;
}
