<?php

use App\Filament\Admin\Resources\ProjectResource;
use App\Filament\Admin\Resources\ProjectResource\Pages\ViewProject;
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
