<?php

use App\Filament\Admin\Resources\ProjectResource;
use App\Filament\Admin\Resources\ProjectResource\Pages\EditProject;
use App\Models\Project;
use App\Models\ProjectRole;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Livewire\livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    Filament::setCurrentPanel(
        Filament::getPanel('admin'),
    );
});

it('should render the roles relation manager', function () {
    $project = Project::factory()->create();

    livewire(ProjectResource\RelationManagers\RolesRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => EditProject::class,
    ])->assertSuccessful();
});

it('should list project roles', function () {
    $project = Project::factory()->create();
    $roles = ProjectRole::factory()->count(3)->create([
        'project_id' => $project->id,
    ]);

    livewire(ProjectResource\RelationManagers\RolesRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => EditProject::class,
    ])
        ->assertCanSeeTableRecords($roles);
});

it('should create a project role', function () {
    $project = Project::factory()->create();
    $role = ProjectRole::factory()->make();

    livewire(ProjectResource\RelationManagers\RolesRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => EditProject::class,
    ])
        ->fillForm([
            'title' => $role->title,
            'description' => $role->description,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(ProjectRole::class, [
        'title' => $role->title,
        'description' => $role->description,
        'project_id' => $project->id,
    ]);
});

it('should validate that the title is required when creating a role', function () {
    $project = Project::factory()->create();

    livewire(ProjectResource\RelationManagers\RolesRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => EditProject::class,
    ])
        ->fillForm([
            'title' => null,
            'description' => 'Test description',
        ])
        ->call('create')
        ->assertHasFormErrors(['title' => 'required']);
});

it('should validate that the title is unique within the project when creating a role', function () {
    $project = Project::factory()->create();
    $existingRole = ProjectRole::factory()->create([
        'project_id' => $project->id,
    ]);

    livewire(ProjectResource\RelationManagers\RolesRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => EditProject::class,
    ])
        ->fillForm([
            'title' => $existingRole->title,
            'description' => 'Test description',
        ])
        ->call('create')
        ->assertHasFormErrors(['title' => 'unique']);
});

it('should edit a project role', function () {
    $project = Project::factory()->create();
    $role = ProjectRole::factory()->create([
        'project_id' => $project->id,
    ]);
    $newData = ProjectRole::factory()->make();

    livewire(ProjectResource\RelationManagers\RolesRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => EditProject::class,
    ])
        ->fillForm([
            'title' => $newData->title,
            'description' => $newData->description,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(ProjectRole::class, [
        'id' => $role->id,
        'title' => $newData->title,
        'description' => $newData->description,
        'project_id' => $project->id,
    ]);
});

it('should delete a project role', function () {
    $project = Project::factory()->create();
    $role = ProjectRole::factory()->create([
        'project_id' => $project->id,
    ]);

    livewire(ProjectResource\RelationManagers\RolesRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => EditProject::class,
    ])
        ->callTableAction('delete', $role);

    $this->assertSoftDeleted($role);
});
