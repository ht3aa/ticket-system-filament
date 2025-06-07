<?php

use App\Filament\Admin\Resources\ProjectResource;
use App\Filament\Admin\Resources\ProjectResource\Pages\CreateProject;
use App\Models\Project;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Livewire\livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    Filament::setCurrentPanel(
        Filament::getPanel('admin'),
    );
});

it('should render the create page', function () {
    $this->get(ProjectResource::getUrl('create'))->assertSuccessful();
});

it('should create a project', function () {
    $project = Project::factory()->make();

    livewire(CreateProject::class)
        ->fillForm([
            'title' => $project->title,
            'description' => $project->description,
        ])
        ->call('create')
        ->assertHasNoFormErrors()
        ->assertHasNoErrors();

    $this->assertDatabaseHas(Project::class, [
        'title' => $project->title,
        'description' => $project->description,
    ]);
});

it('should validate that the title is required', function () {
    $project = Project::factory()->make();

    livewire(CreateProject::class)
        ->fillForm([
            'title' => null,
            'description' => $project->description,
        ])
        ->call('create')
        ->assertHasFormErrors(['title' => 'required']);
});

it('should validate that the title should be unique', function () {
    $project = Project::factory()->create();

    livewire(CreateProject::class)
        ->fillForm([
            'title' => $project->title,
            'description' => $project->description,
        ])
        ->call('create')
        ->assertHasFormErrors(['title' => 'unique']);
});
