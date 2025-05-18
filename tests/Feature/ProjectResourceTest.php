<?php

use Filament\Facades\Filament;
use App\Filament\Admin\Resources\ProjectResource;
use App\Filament\Admin\Resources\ProjectResource\Pages\CreateProject;
use App\Filament\Admin\Resources\ProjectResource\Pages\EditProject;
use App\Filament\Admin\Resources\ProjectResource\Pages\ViewProject;
use App\Models\Project;
use App\Models\User;

use function Pest\Livewire\livewire;

it('should render all the pages of the project resource', function () {
    Filament::setCurrentPanel(
        Filament::getPanel('admin'),
    );

    $this->get(ProjectResource::getUrl('index'))->assertSuccessful();
    $this->get(ProjectResource::getUrl('create'))->assertSuccessful();
    $this->get(ProjectResource::getUrl('edit', ['record' => Project::factory()->create()]))->assertSuccessful();
    $this->get(ProjectResource::getUrl('view', ['record' => Project::factory()->create()]))->assertSuccessful();
});

describe('create, edit and view a project', function () {

    it('should create a project', function () {
        $newProject = Project::factory()->make();
        Filament::setCurrentPanel(
            Filament::getPanel('admin'),
        );

        livewire(CreateProject::class)
            ->fillForm([
                'title' => $newProject->title,
                'description' => $newProject->description,
            ])
            ->call('create')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('projects', [
            'title' => $newProject->title,
            'description' => $newProject->description,
        ]);
    });

    it('should edit a project', function () {
        $newProject = Project::factory()->create();

        Filament::setCurrentPanel(
            Filament::getPanel('admin'),
        );

        livewire(EditProject::class, ['record' => $newProject])
            ->fillForm([
                'title' => 'Updated Project',
                'description' => 'Updated Description',
            ])
            ->call('save')
            ->assertHasNoErrors();

        $this->assertDatabaseHas('projects', [
            'title' => 'Updated Project',
            'description' => 'Updated Description',
        ]);
    });

    it('should view a project', function () {
        $newProject = Project::factory()->create();
        Filament::setCurrentPanel(
            Filament::getPanel('admin'),
        );

        livewire(ViewProject::class, ['record' => $newProject])
            ->assertSuccessful();
    });
});
