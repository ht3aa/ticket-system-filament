<?php

namespace App\Filament\Admin\Resources\ProjectResource\Actions\Table;

use App\Models\Project;
use Filament\Tables\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Grid;
use Filament\Resources\RelationManagers\RelationManager;
use Illuminate\Support\HtmlString;

enum GenerationStatusType: string
{
    case DEFAULT = 'default';
    case CUSTOM = 'custom';
}

class GenerateStatusesAction extends BaseGenerationAction
{
    protected ?string $repeaterFieldName = 'statuses';

    protected ?string $selectFieldName = 'generation_type';

    public static function getDefaultName(): ?string
    {
        return 'generate-statuses';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label('Generate Statuses')
            ->action(function ($data) {
                if ($data['generation_type'] === GenerationStatusType::DEFAULT->value) {
                    $this->generateDefaultStatuses();
                } else if ($data['generation_type'] === GenerationStatusType::CUSTOM->value) {
                    $this->generateCustomStatuses($data['statuses']);
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
                GenerationStatusType::DEFAULT->value => 'Default (Task, Bug, Feature, Urgent)',
                GenerationStatusType::CUSTOM->value => 'Custom (Add your own statuses)',
            ])
            ->default(GenerationStatusType::DEFAULT->value);
    }

    protected function repeaterFormField(): Repeater
    {
        return parent::repeaterFormField()
            ->itemLabel(function ($state, $container) {
                $id = $container->getStatePath();
                return new HtmlString("<span class='font-bold p-2' id='{$id}'>{$state['title']}</span>");
            })
            ->schema(
                function ($livewire) {
                    return [
                        $livewire->titleField()
                            ->extraInputAttributes(function ($component) {
                                $itemComponentStatePath = $component->getContainer()->getParentComponent()->getStatePath();

                                return [
                                    '@keyup' => "() => {
                                                let itemStatusElement = document.getElementById('{$itemComponentStatePath}');
                                                itemStatusElement.innerHTML = \$event.target.value;
                                            }"
                                ];
                            }),

                        $livewire->descriptionField(),
                    ];
                }
            );
    }


    protected function generateDefaultStatuses()
    {
        $statuses = [
            [
                'title' => 'Task',
                'description' => 'A task is a unit of work that needs to be completed.',
            ],
            [
                'title' => 'Bug',
                'description' => 'A bug is an issue that needs to be fixed.',
            ],
            [
                'title' => 'Feature',
                'description' => 'A feature is a new or improved functionality that needs to be added.',
            ],
            [
                'title' => 'Urgent',
                'description' => 'A task is urgent and needs to be completed immediately.',
            ]
        ];

        $statuses = $this->addProjectIdToItems($statuses);

        foreach ($statuses as $status) {
            $this->getModel()::withTrashed()->firstOrCreate(['title' => $status['title'], 'project_id' => $this->project->id], $status);
        }
    }



    protected function generateCustomStatuses($statuses)
    {
        $statuses = $this->addProjectIdToItems($statuses);

        foreach ($statuses as $status) {
            $this->getModel()::create($status);
        }
    }
}
