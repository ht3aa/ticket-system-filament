<?php

use App\Filament\Admin\Resources\ProjectResource;
use App\Filament\Admin\Resources\ProjectResource\Pages\ListProjects;
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

it('should render the list page', function () {
    $this->get(ProjectResource::getUrl('index'))->assertSuccessful();
});

it('should render the title column', function () {
    livewire(ListProjects::class)
        ->assertCanRenderTableColumn('title');
});

it('should render the description column', function () {
    livewire(ListProjects::class)
        ->assertCanRenderTableColumn('description');
});

it('should not display trashed projects by default', function () {
    $projects = Project::factory()->count(4)->create();
    $trashedProjects = Project::factory()->trashed()->count(6)->create();

    livewire(ListProjects::class)
        ->assertCanSeeTableRecords($projects)
        ->assertCanNotSeeTableRecords($trashedProjects);
});

it('should display trashed projects and non-trashed projects when "with Trashed" filter is used', function () {
    $projects = Project::factory()->count(4)->create();
    $trashedProjects = Project::factory()->trashed()->count(6)->create();

    livewire(ListProjects::class)
        ->filterTable('trashed', true)
        ->assertCanSeeTableRecords($trashedProjects)
        ->assertCanSeeTableRecords($projects);
});

it('should display only trashed projects when "only Trashed" filter is used', function () {
    $projects = Project::factory()->count(4)->create();
    $trashedProjects = Project::factory()->trashed()->count(6)->create();

    livewire(ListProjects::class)
        ->filterTable('trashed', false)
        ->assertCanNotSeeTableRecords($projects)
        ->assertCanSeeTableRecords($trashedProjects);
});

it('should delete a record when the delete action is used', function () {
    $project = Project::factory()->create();

    livewire(ListProjects::class)
        ->callTableAction(DeleteAction::class, $project);

    $this->assertSoftDeleted($project);
});

it('should restore a record when the restore action is used', function () {
    $project = Project::factory()->create();

    $project->delete();

    livewire(ListProjects::class)
        ->filterTable('trashed', true)
        ->callTableAction(RestoreAction::class, $project);

    $this->assertNotSoftDeleted($project);
});

it('should force delete a record when the force delete action is used', function () {
    $project = Project::factory()->create();

    $project->delete();

    livewire(ListProjects::class)
        ->filterTable('trashed', true)
        ->callTableAction(ForceDeleteAction::class, $project);

    $this->assertDatabaseMissing(Project::class, $project->toArray());
});

it('should search for a project', function () {
    $projects = Project::factory()->count(4)->create();

    $project = $projects->first();

    livewire(ListProjects::class)
        ->searchTable($project->title)
        ->assertCanSeeTableRecords($projects->where('title', $project->title))
        ->assertCanNotSeeTableRecords($projects->where('title', '!=', $project->title));
});
