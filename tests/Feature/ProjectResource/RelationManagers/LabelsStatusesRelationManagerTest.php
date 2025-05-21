<?php

use App\Filament\Admin\Resources\ProjectResource;
use App\Filament\Admin\Resources\ProjectResource\Pages\EditProject;
use App\Models\Project;
use App\Models\ProjectLabel;
use App\Models\ProjectLabelStatus;
use App\Models\ProjectStatus;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Livewire\livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    Filament::setCurrentPanel(
        Filament::getPanel('admin'),
    );
});

it('should render the labels statuses relation manager', function () {
    $project = Project::factory()->create();

    livewire(ProjectResource\RelationManagers\LabelsStatusesRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => EditProject::class,
    ])->assertSuccessful();
});

it('should list project label statuses', function () {
    $project = Project::factory()->create();
    $labelStatuses = ProjectLabelStatus::factory()->count(3)->create([
        'project_id' => $project->id,
    ]);

    livewire(ProjectResource\RelationManagers\LabelsStatusesRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => EditProject::class,
    ])
        ->assertCanSeeTableRecords($labelStatuses);
});

it('should create a project label status', function () {
    $project = Project::factory()->create();
    $label = ProjectLabel::factory()->create(['project_id' => $project->id]);
    $status = ProjectStatus::factory()->create(['project_id' => $project->id]);

    livewire(ProjectResource\RelationManagers\LabelsStatusesRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => EditProject::class,
    ])
        ->fillForm([
            'project_label_id' => $label->id,
            'project_status_id' => $status->id,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(ProjectLabelStatus::class, [
        'project_label_id' => $label->id,
        'project_status_id' => $status->id,
        'project_id' => $project->id,
    ]);
});

it('should validate that the label is required when creating a label status', function () {
    $project = Project::factory()->create();
    $status = ProjectStatus::factory()->create(['project_id' => $project->id]);

    livewire(ProjectResource\RelationManagers\LabelsStatusesRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => EditProject::class,
    ])
        ->fillForm([
            'project_label_id' => null,
            'project_status_id' => $status->id,
        ])
        ->call('create')
        ->assertHasFormErrors(['project_label_id' => 'required']);
});

it('should validate that the status is required when creating a label status', function () {
    $project = Project::factory()->create();
    $label = ProjectLabel::factory()->create(['project_id' => $project->id]);

    livewire(ProjectResource\RelationManagers\LabelsStatusesRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => EditProject::class,
    ])
        ->fillForm([
            'project_label_id' => $label->id,
            'project_status_id' => null,
        ])
        ->call('create')
        ->assertHasFormErrors(['project_status_id' => 'required']);
});

it('should validate that the label-status combination is unique within the project', function () {
    $project = Project::factory()->create();
    $label = ProjectLabel::factory()->create(['project_id' => $project->id]);
    $status = ProjectStatus::factory()->create(['project_id' => $project->id]);

    ProjectLabelStatus::factory()->create([
        'project_id' => $project->id,
        'project_label_id' => $label->id,
        'project_status_id' => $status->id,
    ]);

    livewire(ProjectResource\RelationManagers\LabelsStatusesRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => EditProject::class,
    ])
        ->fillForm([
            'project_label_id' => $label->id,
            'project_status_id' => $status->id,
        ])
        ->call('create')
        ->assertHasFormErrors(['project_label_id' => 'unique']);
});

it('should delete a project label status', function () {
    $project = Project::factory()->create();
    $labelStatus = ProjectLabelStatus::factory()->create([
        'project_id' => $project->id,
    ]);

    livewire(ProjectResource\RelationManagers\LabelsStatusesRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => EditProject::class,
    ])
        ->callTableAction('delete', $labelStatus);

    $this->assertSoftDeleted($labelStatus);
});
