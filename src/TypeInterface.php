<?php

namespace KamilSawicki\LaravelFormBuilder;

interface TypeInterface
{
    public function getValue(): mixed;

    public function getName(): string;

    public function getLabel(): string;

    public function renderControl(): string;

    public function renderLaber(): string;

    public function render(): string;
}
