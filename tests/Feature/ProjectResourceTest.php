<?php

use Filament\Facades\Filament;
use App\Filament\Admin\Resources\ProjectResource;
use App\Filament\Admin\Resources\ProjectResource\Pages\CreateProject;
use App\Filament\Admin\Resources\ProjectResource\Pages\EditProject;
use App\Filament\Admin\Resources\ProjectResource\Pages\ListProjects;
use App\Filament\Admin\Resources\ProjectResource\Pages\ViewProject;
use App\Models\Project;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Table;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Livewire\livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    Filament::setCurrentPanel(
        Filament::getPanel('admin'),
    );
});


describe('project create page', function () {
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
});

describe('project edit page', function () {
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
});


describe('project deleting actions', function () {
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
});

describe('project view page', function () {
    it('should render the view page', function () {
        $this->get(ProjectResource::getUrl('view', ['record' => Project::factory()->create()]))->assertSuccessful();
    });

    it('should retrieve a project', function () {
        $project = Project::factory()->create();

        livewire(ViewProject::class, ['record' => $project->getRouteKey()])
            ->assertFormSet([
                'title' => $project->title,
                'description' => $project->description,
            ]);
    });
});


describe('project list page', function () {
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

    it('should have pagination of 10, 20, 50, 100', function () {

        livewire(ListProjects::class)
            ->call('getTable')
            ->assertSeeHtml('
                    <option value="5">
                        5
                    </option>
                    <option value="10">
                        10
                    </option>
                    <option value="25">
                        25
                    </option>
                    <option value="50">
                        50
                    </option>
                    <option value="all">
                        All
                    </option>
        ');
    });
});
