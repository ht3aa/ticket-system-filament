<?php

namespace App\Filament\Admin\Resources\ProjectResource\Actions\Table;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Grid;
use Illuminate\Support\HtmlString;

enum GenerationLabelType: string
{
    case DEFAULT = 'default';
    case CUSTOM = 'custom';
}

class GenerateLabelsAction extends BaseGenerationAction
{
    protected ?string $repeaterFormFieldName = 'labels';

    protected ?string $selectFormFieldName = 'generation_type';

    public static function getDefaultName(): ?string
    {
        return 'generate-labels';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label('Generate Labels')
            ->action(function ($data) {
                if ($data['generation_type'] === GenerationLabelType::DEFAULT->value) {
                    $this->generateDefaultLabels();
                } else if ($data['generation_type'] === GenerationLabelType::CUSTOM->value) {
                    $this->saveItems($data['labels']);
                }
            });
    }

    protected function formSchema(): array
    {
        return [
            $this->selectFormField(),
            $this->repeaterFormField(),
        ];
    }

    protected function selectFormField(): Select
    {
        return parent::selectFormField()
            ->options([
                GenerationLabelType::DEFAULT->value => 'Default (Task, Bug, Feature, Urgent)',
                GenerationLabelType::CUSTOM->value => 'Custom (Add your own labels)',
            ])
            ->default(GenerationLabelType::DEFAULT->value);
    }

    protected function repeaterFormField(): Repeater
    {
        return parent::repeaterFormField()
            ->itemLabel(function ($state, $container) {
                $id = $container->getStatePath();
                return new HtmlString("<span class='font-bold p-2' style='background-color: {$state['color']}' id='{$id}'>{$state['title']}</span>");
            })
            ->schema(
                function ($livewire) {
                    return [
                        Grid::make(2)
                            ->schema([
                                $livewire->titleField()
                                    ->extraInputAttributes(function ($component) {
                                        $itemComponentStatePath = $component->getContainer()->getParentComponent()->getStatePath();

                                        return [
                                            '@keyup' => "() => {
                                                        let itemLabelElement = document.getElementById('{$itemComponentStatePath}');
                                                        itemLabelElement.innerHTML = \$event.target.value;
                                                    }"
                                        ];
                                    }),

                                $livewire->colorField()
                                    ->extraInputAttributes(function ($component) {
                                        $itemComponentStatePath = $component->getContainer()->getParentComponent()->getStatePath();

                                        return [
                                            '@blur' => "() => {
                                                        let itemLabelElement = document.getElementById('{$itemComponentStatePath}');
                                                        itemLabelElement.style.backgroundColor = \$event.target.value;
                                                    }"
                                        ];
                                    })
                            ]),

                        $livewire->descriptionField(),
                    ];
                }
            );
    }



    protected function generateDefaultLabels()
    {
        $this->saveItems([
            [
                'title' => 'Task',
                'color' => '#0000ff', // blue
                'description' => 'A task is a unit of work that needs to be completed.',
            ],
            [
                'title' => 'Bug',
                'color' => '#ffd700', // yellow
                'description' => 'A bug is an issue that needs to be fixed.',
            ],
            [
                'title' => 'Feature',
                'color' => '#00ff00', // green
                'description' => 'A feature is a new or improved functionality that needs to be added.',
            ],
            [
                'title' => 'Urgent',
                'color' => '#ff0000', // red
                'description' => 'A task is urgent and needs to be completed immediately.',
            ]
        ]);
    }
}
