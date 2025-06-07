<?php

use App\Filament\Admin\Resources\ProjectResource;
use App\Filament\Admin\Resources\ProjectResource\Pages\EditProject;
use App\Models\Project;
use App\Models\ProjectMember;
use App\Models\ProjectRole;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Filament\Actions\CreateAction;
use Filament\Actions\EditAction;
use Filament\Actions\DeleteAction;

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
        ->mountTableAction(CreateAction::class)
        ->setTableActionData([
            'user_id' => $member->user_id,
            'project_role_id' => $member->project_role_id,
        ])
        ->callMountedTableAction()
        ->assertHasNoTableActionErrors();

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
        ->mountTableAction(CreateAction::class)
        ->setTableActionData([
            'user_id' => null,
            'project_role_id' => ProjectRole::factory()->create()->id,
        ])
        ->callMountedTableAction()
        ->assertHasTableActionErrors(['user_id' => 'required']);
});

it('should validate that the project role is required when creating a member', function () {
    $project = Project::factory()->create();
    $member = ProjectMember::factory()->make();

    livewire(ProjectResource\RelationManagers\MembersRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => EditProject::class,
    ])
        ->mountTableAction(CreateAction::class)
        ->setTableActionData([
            'user_id' => $member->user_id,
            'project_role_id' => null,
        ])
        ->callMountedTableAction()
        ->assertHasTableActionErrors(['project_role_id' => 'required']);
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
        ->mountTableAction(CreateAction::class)
        ->setTableActionData([
            'user_id' => $existingMember->user_id,
            'project_role_id' => $existingMember->project_role_id,
        ])
        ->callMountedTableAction()
        ->assertHasTableActionErrors(['user_id' => 'unique']);
});

it('should edit a project member', function () {
    $project = Project::factory()->create();
    $member = ProjectMember::factory()->create([
        'project_id' => $project->id,
    ]);
    $newData = ProjectMember::factory()->make();

    livewire(ProjectResource\RelationManagers\MembersRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => EditProject::class,
    ])
        ->mountTableAction(EditAction::class, $member)
        ->setTableActionData([
            'user_id' => $newData->user_id,
            'project_role_id' => $newData->project_role_id,
        ])
        ->callMountedTableAction()
        ->assertHasNoTableActionErrors();

    $this->assertDatabaseHas(ProjectMember::class, [
        'id' => $member->id,
        'user_id' => $newData->user_id,
        'project_role_id' => $newData->project_role_id,
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
        ->mountTableAction(DeleteAction::class, $member)
        ->callMountedTableAction()
        ->assertHasNoTableActionErrors();

    $this->assertSoftDeleted($member);
});
