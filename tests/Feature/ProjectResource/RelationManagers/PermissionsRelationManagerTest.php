<?php

use App\Filament\Admin\Resources\ProjectResource;
use App\Filament\Admin\Resources\ProjectResource\Pages\EditProject;
use App\Models\Project;
use App\Models\ProjectPermission;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Livewire\livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    Filament::setCurrentPanel(
        Filament::getPanel('admin'),
    );
});

it('should render the permissions relation manager', function () {
    $project = Project::factory()->create();

    livewire(ProjectResource\RelationManagers\PermissionsRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => EditProject::class,
    ])->assertSuccessful();
});

it('should list project permissions', function () {
    $project = Project::factory()->create();
    $permissions = ProjectPermission::factory()->count(3)->create([
        'project_id' => $project->id,
    ]);

    livewire(ProjectResource\RelationManagers\PermissionsRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => EditProject::class,
    ])
        ->assertCanSeeTableRecords($permissions);
});

it('should create a project permission', function () {
    $project = Project::factory()->create();
    $permission = ProjectPermission::factory()->make();

    livewire(ProjectResource\RelationManagers\PermissionsRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => EditProject::class,
    ])
        ->fillForm([
            'title' => $permission->title,
            'description' => $permission->description,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(ProjectPermission::class, [
        'title' => $permission->title,
        'description' => $permission->description,
        'project_id' => $project->id,
    ]);
});

it('should validate that the title is required when creating a permission', function () {
    $project = Project::factory()->create();

    livewire(ProjectResource\RelationManagers\PermissionsRelationManager::class, [
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

it('should validate that the title is unique within the project when creating a permission', function () {
    $project = Project::factory()->create();
    $existingPermission = ProjectPermission::factory()->create([
        'project_id' => $project->id,
    ]);

    livewire(ProjectResource\RelationManagers\PermissionsRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => EditProject::class,
    ])
        ->fillForm([
            'title' => $existingPermission->title,
            'description' => 'Test description',
        ])
        ->call('create')
        ->assertHasFormErrors(['title' => 'unique']);
});

it('should edit a project permission', function () {
    $project = Project::factory()->create();
    $permission = ProjectPermission::factory()->create([
        'project_id' => $project->id,
    ]);
    $newData = ProjectPermission::factory()->make();

    livewire(ProjectResource\RelationManagers\PermissionsRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => EditProject::class,
    ])
        ->fillForm([
            'title' => $newData->title,
            'description' => $newData->description,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(ProjectPermission::class, [
        'id' => $permission->id,
        'title' => $newData->title,
        'description' => $newData->description,
        'project_id' => $project->id,
    ]);
});

it('should delete a project permission', function () {
    $project = Project::factory()->create();
    $permission = ProjectPermission::factory()->create([
        'project_id' => $project->id,
    ]);

    livewire(ProjectResource\RelationManagers\PermissionsRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => EditProject::class,
    ])
        ->callTableAction('delete', $permission);

    $this->assertSoftDeleted($permission);
});
