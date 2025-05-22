<?php

use App\Filament\Admin\Resources\ProjectResource;
use App\Filament\Admin\Resources\ProjectResource\Actions\Table\HasChildrenAction;
use App\Filament\Admin\Resources\ProjectResource\Pages\EditProject;
use App\Models\Project;
use App\Models\ProjectLabel;
use App\Models\Ticket;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;
use Filament\Facades\Filament;
use Filament\Tables\Actions\DeleteAction as ActionsDeleteAction;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Livewire\livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    Filament::setCurrentPanel(
        Filament::getPanel('admin'),
    );
});

it('should render the labels relation manager', function () {
    $project = Project::factory()->create();

    livewire(ProjectResource\RelationManagers\LabelsRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => EditProject::class,
    ])->assertSuccessful();
});

it('should list project labels', function () {
    $project = Project::factory()->create();
    $labels = ProjectLabel::factory()->count(3)->create([
        'project_id' => $project->id,
    ]);

    livewire(ProjectResource\RelationManagers\LabelsRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => EditProject::class,
    ])
        ->assertCanSeeTableRecords($labels);
});

it('should create a project label', function () {
    $project = Project::factory()->create();
    $label = ProjectLabel::factory()->make();

    livewire(ProjectResource\RelationManagers\LabelsRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => EditProject::class,
    ])
        ->mountTableAction(CreateAction::class)
        ->setTableActionData([
            'title' => $label->title,
            'description' => $label->description,
            'color' => $label->color,
        ])
        ->callMountedTableAction()
        ->assertHasNoTableActionErrors();

    $this->assertDatabaseHas(ProjectLabel::class, [
        'title' => $label->title,
        'description' => $label->description,
        'color' => $label->color,
        'project_id' => $project->id,
    ]);
});

it('should validate that the title is required when creating a label', function () {
    $project = Project::factory()->create();

    livewire(ProjectResource\RelationManagers\LabelsRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => EditProject::class,
    ])
        ->mountTableAction(CreateAction::class)
        ->setTableActionData([
            'title' => null,
            'description' => 'Test description',
            'color' => '#000000',
        ])
        ->callMountedTableAction()
        ->assertHasTableActionErrors(['title' => 'required']);
});

it('should validate that the color is required when creating a label', function () {
    $project = Project::factory()->create();

    livewire(ProjectResource\RelationManagers\LabelsRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => EditProject::class,
    ])
        ->mountTableAction(CreateAction::class)
        ->setTableActionData([
            'title' => 'Test Label',
            'description' => 'Test description',
            'color' => null,
        ])
        ->callMountedTableAction()
        ->assertHasTableActionErrors(['color' => 'required']);
});

it('should validate that the title is unique within the project when creating a label', function () {
    $project = Project::factory()->create();
    $existingLabel = ProjectLabel::factory()->create([
        'project_id' => $project->id,
    ]);

    livewire(ProjectResource\RelationManagers\LabelsRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => EditProject::class,
    ])
        ->mountTableAction(CreateAction::class)
        ->setTableActionData([
            'title' => $existingLabel->title,
            'description' => 'Test description',
            'color' => '#000000',
        ])
        ->callMountedTableAction()
        ->assertHasTableActionErrors(['title' => 'unique']);
});

it('should edit a project label', function () {
    $project = Project::factory()->create();
    $label = ProjectLabel::factory()->create([
        'project_id' => $project->id,
    ]);
    $newData = ProjectLabel::factory()->make();

    livewire(ProjectResource\RelationManagers\LabelsRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => EditProject::class,
    ])
        ->mountTableAction(EditAction::class, $label)
        ->setTableActionData([
            'title' => $newData->title,
            'description' => $newData->description,
            'color' => $newData->color,
        ])
        ->callMountedTableAction()
        ->assertHasNoTableActionErrors();

    $this->assertDatabaseHas(ProjectLabel::class, [
        'id' => $label->id,
        'title' => $newData->title,
        'description' => $newData->description,
        'color' => $newData->color,
        'project_id' => $project->id,
    ]);
});

it('should delete a project label', function () {
    $project = Project::factory()->create();
    $label = ProjectLabel::factory()->create([
        'project_id' => $project->id,
    ]);

    livewire(ProjectResource\RelationManagers\LabelsRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => EditProject::class,
    ])
        ->mountTableAction(DeleteAction::class, $label)
        ->callMountedTableAction()
        ->assertHasNoTableActionErrors();

    $this->assertSoftDeleted($label);
});

// TODO: fix this test
// it('should not allow deletion of label with children', function () {
//     $project = Project::factory()->create();
//     $label = ProjectLabel::factory()->create([
//         'project_id' => $project->id,
//     ]);

//     // create a ticket with the label
//     Ticket::factory()->create([
//         'project_id' => $project->id,
//         'project_label_id' => $label->id,
//     ]);

//     livewire(ProjectResource\RelationManagers\LabelsRelationManager::class, [
//         'ownerRecord' => $project,
//         'pageClass' => EditProject::class,
//     ])
//         ->assertTableActionExists(DeleteAction::class);
// });
