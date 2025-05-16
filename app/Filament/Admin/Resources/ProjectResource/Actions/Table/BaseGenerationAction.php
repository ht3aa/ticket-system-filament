<?php

namespace App\Filament\Admin\Resources\ProjectResource\Actions\Table;

use App\Models\Project;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\Action;
use Filament\Resources\RelationManagers\RelationManager;


class BaseGenerationAction extends Action
{
    protected Project $project;

    protected ?string $repeaterFieldName = null;

    protected ?string $selectFieldName = null;


    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->form($this->formSchema())
            ->authorize(static fn(RelationManager $livewire): bool => (! $livewire->isReadOnly()));
    }

    protected function selectFormField(): Select
    {
        return Select::make($this->selectFieldName)
            ->native(false)
            ->required()
            ->extraInputAttributes(function ($component) {
                $repeaterStatePath = str($component->getStatePath())->before('.' . $component->getName()) . '.' . $this->repeaterFieldName;
                $selectStatePath = $component->getStatePath();

                return [
                    'x-init' => "() => {
                        const repeater = document.querySelector('[data-id=\"{$this->repeaterFieldName}-repeater\"]');
                        const form = repeater.closest('form');
                        const select = document.getElementById('{$selectStatePath}');

                        form.addEventListener('submit', () => {
                            if (select.value === '" . GenerationLabelType::DEFAULT->value . "') {
                                \$wire.set('{$repeaterStatePath}', null, false);
                            }
                        });

                        if (repeater) {
                            repeater.style.display = 'none';
                        }
                    }",
                    '@change' => "() => {
                        const repeater = document.querySelector('[data-id=\"{$this->repeaterFieldName}-repeater\"]');

                        if (\$event.target.value === '" . GenerationLabelType::CUSTOM->value . "') {
                            repeater.style.display = 'block';
                        } else {
                            repeater.style.display = 'none';
                        }
                    }"
                ];
            });
    }

    protected function repeaterFormField(): Repeater
    {
        return Repeater::make($this->repeaterFieldName)
            ->extraFieldWrapperAttributes([
                'data-id' => "{$this->repeaterFieldName}-repeater",
            ])
            ->reorderable(false)
            ->collapsible(true);
    }


    protected function addProjectIdToItems($items)
    {
        return array_map(function ($item) {
            $item['project_id'] = $this->project->id;
            return $item;
        }, $items);
    }

    public function projectRecord(Project $project)
    {
        $this->project = $project;

        return $this;
    }
}
