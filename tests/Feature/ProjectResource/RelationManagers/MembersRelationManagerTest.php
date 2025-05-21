<?php

use App\Filament\Admin\Resources\ProjectResource;
use App\Filament\Admin\Resources\ProjectResource\Pages\EditProject;
use App\Models\Project;
use App\Models\ProjectMember;
use App\Models\ProjectRole;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Livewire\livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    Filament::setCurrentPanel(
        Filament::getPanel('admin'),
    );
});

it('should render the members relation manager', function () {
    $project = Project::factory()->create();

    livewire(ProjectResource\RelationManagers\MembersRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => EditProject::class,
    ])->assertSuccessful();
});

it('should list project members', function () {
    $project = Project::factory()->create();
    $members = ProjectMember::factory()->count(3)->create([
        'project_id' => $project->id,
    ]);

    livewire(ProjectResource\RelationManagers\MembersRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => EditProject::class,
    ])
        ->assertCanSeeTableRecords($members);
});

it('should create a project member', function () {
    $project = Project::factory()->create();
    $member = ProjectMember::factory()->make();

    livewire(ProjectResource\RelationManagers\MembersRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => EditProject::class,
    ])
        ->fillForm([
            'user_id' => $member->user_id,
            'project_role_id' => $member->project_role_id,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(ProjectMember::class, [
        'user_id' => $member->user_id,
        'project_role_id' => $member->project_role_id,
        'project_id' => $project->id,
    ]);
});

it('should validate that the user is required when creating a member', function () {
    $project = Project::factory()->create();

    livewire(ProjectResource\RelationManagers\MembersRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => EditProject::class,
    ])
        ->fillForm([
            'user_id' => null,
            'project_role_id' => ProjectRole::factory()->create(['project_id' => $project->id])->id,
        ])
        ->call('create')
        ->assertHasFormErrors(['user_id' => 'required']);
});

it('should validate that the role is required when creating a member', function () {
    $project = Project::factory()->create();

    livewire(ProjectResource\RelationManagers\MembersRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => EditProject::class,
    ])
        ->fillForm([
            'user_id' => User::factory()->create()->id,
            'project_role_id' => null,
        ])
        ->call('create')
        ->assertHasFormErrors(['project_role_id' => 'required']);
});

it('should validate that the user is unique within the project when creating a member', function () {
    $project = Project::factory()->create();
    $existingMember = ProjectMember::factory()->create([
        'project_id' => $project->id,
    ]);

    livewire(ProjectResource\RelationManagers\MembersRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => EditProject::class,
    ])
        ->fillForm([
            'user_id' => $existingMember->user_id,
            'project_role_id' => ProjectRole::factory()->create(['project_id' => $project->id])->id,
        ])
        ->call('create')
        ->assertHasFormErrors(['user_id' => 'unique']);
});

it('should edit a project member', function () {
    $project = Project::factory()->create();
    $member = ProjectMember::factory()->create([
        'project_id' => $project->id,
    ]);
    $newRole = ProjectRole::factory()->create([
        'project_id' => $project->id,
    ]);

    livewire(ProjectResource\RelationManagers\MembersRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => EditProject::class,
    ])
        ->fillForm([
            'user_id' => $member->user_id,
            'project_role_id' => $newRole->id,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(ProjectMember::class, [
        'id' => $member->id,
        'user_id' => $member->user_id,
        'project_role_id' => $newRole->id,
        'project_id' => $project->id,
    ]);
});

it('should delete a project member', function () {
    $project = Project::factory()->create();
    $member = ProjectMember::factory()->create([
        'project_id' => $project->id,
    ]);

    livewire(ProjectResource\RelationManagers\MembersRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => EditProject::class,
    ])
        ->callTableAction('delete', $member);

    $this->assertSoftDeleted($member);
});
