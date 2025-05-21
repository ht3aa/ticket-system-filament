<?php

use App\Filament\Admin\Resources\ProjectResource;
use App\Filament\Admin\Resources\ProjectResource\Pages\EditProject;
use App\Models\Project;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Livewire\livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    Filament::setCurrentPanel(
        Filament::getPanel('admin'),
    );
});

it('should render the edit page', function () {
    $this->get(ProjectResource::getUrl('edit', ['record' => Project::factory()->create()]))->assertSuccessful();
});

it('should retrieve a project', function () {
    $project = Project::factory()->create();

    livewire(EditProject::class, ['record' => $project->getRouteKey()])
        ->assertFormSet([
            'title' => $project->title,
            'description' => $project->description,
        ]);
});

it('should edit a project', function () {
    $project = Project::factory()->create();

    livewire(EditProject::class, ['record' => $project->getRouteKey()])
        ->fillForm([
            'title' => $project->title,
            'description' => $project->description,
        ])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertHasNoErrors();

    $this->assertDatabaseHas(Project::class, [
        'title' => $project->title,
        'description' => $project->description,
    ]);
});

it('should validate that the title is required', function () {
    $project = Project::factory()->create();

    livewire(EditProject::class, ['record' => $project->getRouteKey()])
        ->fillForm([
            'title' => null,
            'description' => $project->description,
        ])
        ->call('save')
        ->assertHasFormErrors(['title' => 'required']);
});

it('should validate that the title should be unique', function () {
    $project = Project::factory()->create();

    livewire(EditProject::class, ['record' => $project->getRouteKey()])
        ->fillForm([
            'title' => $project->title,
            'description' => $project->description,
        ])
        ->call('save')
        ->assertHasNoFormErrors()
        ->assertHasNoErrors();
});

it('should soft delete a project', function () {
    $project = Project::factory()->create();

    livewire(EditProject::class, ['record' => $project->getRouteKey()])
        ->callAction(DeleteAction::class)
        ->assertHasNoFormErrors()
        ->assertHasNoErrors();

    $this->assertSoftDeleted($project);
});

it('should restore the soft deleted project', function () {
    $project = Project::factory()->create();

    $project->delete();

    livewire(EditProject::class, ['record' => $project->getRouteKey()])
        ->callAction(RestoreAction::class)
        ->assertHasNoFormErrors()
        ->assertHasNoErrors();

    $this->assertNotSoftDeleted($project);
});

it('should force delete a project', function () {
    $project = Project::factory()->create();

    $project->delete();

    livewire(EditProject::class, ['record' => $project->getRouteKey()])
        ->callAction(ForceDeleteAction::class)
        ->assertHasNoFormErrors()
        ->assertHasNoErrors();

    $this->assertDatabaseMissing(Project::class, $project->toArray());
});
