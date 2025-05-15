<?php

namespace App\Filament\Admin\Resources\ProjectResource\Actions\Table;

use App\Models\Project;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Grid;
use Illuminate\Support\HtmlString;

enum GenerationType: string
{
    case DEFAULT = 'default';
    case CUSTOM = 'custom';
}

class GenerateLabels extends Action
{
    protected Project $project;

    public static function getDefaultName(): ?string
    {
        return 'generate-labels';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label('Generate Labels')
            ->form($this->formSchema())
            ->action(function ($data) {
                if ($data['generation_type'] === GenerationType::DEFAULT->value) {
                    $this->generateDefaultLabels();
                } else if ($data['generation_type'] === GenerationType::CUSTOM->value) {
                    $this->generateCustomLabels($data['labels']);
                }
            });
    }

    private function formSchema(): array
    {
        return [
            $this->selectFormField(),
            $this->repeaterFormField(),
        ];
    }

    private function selectFormField(): Select
    {
        return Select::make('generation_type')
            ->native(false)
            ->required()
            ->options([
                GenerationType::DEFAULT->value => 'Default (Task, Bug, Feature, Urgent)',
                GenerationType::CUSTOM->value => 'Custom (Add your own labels)',
            ])
            ->default(GenerationType::DEFAULT->value)
            ->extraInputAttributes(function ($component) {
                $repeaterStatePath = str($component->getStatePath())->before('.' . $component->getName()) . '.labels';
                $selectStatePath = $component->getStatePath();

                return [
                    'x-init' => "() => {
                        const repeater = document.querySelector('[data-id=\"labels-repeater\"]');
                        const form = repeater.closest('form');
                        const select = document.getElementById('{$selectStatePath}');

                        form.addEventListener('submit', () => {
                            if (select.value === '" . GenerationType::DEFAULT->value . "') {
                                \$wire.set('{$repeaterStatePath}', null, false);
                            }
                        });

                        if (repeater) {
                            repeater.style.display = 'none';

                        }
                    }",
                    '@change' => "() => {
                        const repeater = document.querySelector('[data-id=\"labels-repeater\"]');

                        if (\$event.target.value === '" . GenerationType::CUSTOM->value . "') {
                            repeater.style.display = 'block';
                        } else {
                            repeater.style.display = 'none';
                        }
                    }"
                ];
            });
    }

    private function repeaterFormField(): Repeater
    {
        return Repeater::make('labels')
            ->extraFieldWrapperAttributes([
                'data-id' => 'labels-repeater',
                'class' => 'hidden',
            ])
            ->reorderable(false)
            ->itemLabel(function ($state, $container) {
                $id = $container->getStatePath();
                return new HtmlString("<span class='font-bold p-2' style='background-color: {$state['color']}' id='{$id}'>{$state['title']}</span>");
            })
            ->collapsible(true)
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


    public function projectRecord(Project $project)
    {
        $this->project = $project;
        return $this;
    }

    private function generateDefaultLabels()
    {
        $labels = [
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
        ];

        // Add project_id to each item
        $labels = $this->addProjectIdToLabels($labels);

        foreach ($labels as $label) {
            $this->getModel()::withTrashed()->firstOrCreate(['title' => $label['title'], 'project_id' => $this->project->id], $label);
        }
    }

    private function addProjectIdToLabels($labels)
    {
        return array_map(function ($item) {
            $item['project_id'] = $this->project->id;
            return $item;
        }, $labels);
    }

    private function generateCustomLabels($labels)
    {
        $labels = $this->addProjectIdToLabels($labels);

        foreach ($labels as $label) {
            $this->getModel()::create($label);
        }
    }
}
