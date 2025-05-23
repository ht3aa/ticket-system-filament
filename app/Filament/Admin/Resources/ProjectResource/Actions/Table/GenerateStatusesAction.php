<?php

namespace App\Filament\Admin\Resources\ProjectResource\Actions\Table;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Illuminate\Support\HtmlString;

enum GenerationStatusType: string
{
    case DEFAULT = 'default';
    case CUSTOM = 'custom';
}

class GenerateStatusesAction extends BaseGenerationAction
{
    protected ?string $repeaterFormFieldName = 'statuses';

    protected ?string $selectFormFieldName = 'generation_type';

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
                } elseif ($data['generation_type'] === GenerationStatusType::CUSTOM->value) {
                    $this->saveItems($data['statuses']);
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
                GenerationStatusType::DEFAULT->value => 'Default (To Do, In Progress, Done)',
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
                                            }",
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
                'title' => 'To Do',
                'description' => 'A task is a unit of work that needs to be completed.',
            ],
            [
                'title' => 'In Progress',
                'description' => 'A task is in progress.',
            ],
            [
                'title' => 'Done',
                'description' => 'A task is done.',
            ],

        ];

        $this->saveItems($statuses);
    }
}
