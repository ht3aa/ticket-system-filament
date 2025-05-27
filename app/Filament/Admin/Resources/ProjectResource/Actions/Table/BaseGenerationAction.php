<?php

namespace App\Filament\Admin\Resources\ProjectResource\Actions\Table;

use App\Models\Project;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Actions\Action;

class BaseGenerationAction extends Action
{
    protected Project $project;

    protected ?string $repeaterFormFieldName = null;

    protected ?string $selectFormFieldName = null;

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->form($this->formSchema())
            ->icon('heroicon-m-adjustments-vertical')
            ->authorize(static fn(RelationManager $livewire): bool => (! $livewire->isReadOnly()));
    }

    protected function selectFormField(): Select
    {
        return Select::make($this->selectFormFieldName)
            ->required()
            ->extraInputAttributes(function ($component) {
                $repeaterStatePath = str($component->getStatePath())->before('.' . $component->getName()) . '.' . $this->repeaterFormFieldName;
                $selectStatePath = $component->getStatePath();

                return [
                    'x-init' => "() => {
                        const repeater = document.querySelector('[data-id=\"{$this->repeaterFormFieldName}-repeater\"]');
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
                        const repeater = document.querySelector('[data-id=\"{$this->repeaterFormFieldName}-repeater\"]');

                        if (\$event.target.value === '" . GenerationLabelType::CUSTOM->value . "') {
                            repeater.style.display = 'block';
                        } else {
                            repeater.style.display = 'none';
                        }
                    }",
                ];
            });
    }

    protected function repeaterFormField(): Repeater
    {
        return Repeater::make($this->repeaterFormFieldName)
            ->extraFieldWrapperAttributes([
                'data-id' => "{$this->repeaterFormFieldName}-repeater",
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

    protected function saveItems($items)
    {
        $items = $this->addProjectIdToItems($items);

        foreach ($items as $item) {
            $this->getModel()::withTrashed()->firstOrCreate(['title' => $item['title'], 'project_id' => $this->project->id], $item);
        }
    }
}
