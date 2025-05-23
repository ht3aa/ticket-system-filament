<?php

namespace App\Filament\Admin\Resources\ProjectResource\Actions\Table;

use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Illuminate\Support\HtmlString;

enum GenerationRoleType: string
{
    case DEFAULT = 'default';
    case CUSTOM = 'custom';
}

class GenerateRolesAction extends BaseGenerationAction
{
    protected ?string $repeaterFormFieldName = 'roles';

    protected ?string $selectFormFieldName = 'generation_type';

    public static function getDefaultName(): ?string
    {
        return 'generate-roles';
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this
            ->label('Generate Roles')
            ->action(function ($data) {
                if ($data['generation_type'] === GenerationRoleType::DEFAULT->value) {
                    $this->generateDefaultRoles();
                } elseif ($data['generation_type'] === GenerationRoleType::CUSTOM->value) {
                    $this->saveItems($data['roles']);
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
                GenerationRoleType::DEFAULT->value => 'Default (Admin, Manager, Developer, Tester)',
                GenerationRoleType::CUSTOM->value => 'Custom (Add your own roles)',
            ])
            ->default(GenerationRoleType::DEFAULT->value);
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
                        Grid::make(2)
                            ->schema([
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
                            ]),
                    ];
                }
            );
    }

    protected function generateDefaultRoles()
    {
        $roles = [
            [
                'title' => 'Admin',
                'description' => 'A task is a unit of work that needs to be completed.',
            ],
            [
                'title' => 'Manager',
                'description' => 'A task is in progress.',
            ],
            [
                'title' => 'Developer',
                'description' => 'A task is done.',
            ],
            [
                'title' => 'Tester',
                'description' => 'A task is done.',
            ],

        ];

        $this->saveItems($roles);
    }
}
