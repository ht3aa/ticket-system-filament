<?php

use Filament\Facades\Filament;
use App\Filament\Admin\Resources\ProjectResource;
use App\Filament\Admin\Resources\ProjectResource\Pages\ListProjects;
use App\Models\Project;
use App\Models\User;

use function Pest\Livewire\livewire;

it('should render the list page', function () {
    Filament::setCurrentPanel(
        Filament::getPanel('admin'),
    );

    livewire(ListProjects::class)
        ->assertCanRenderTableColumn('title')
        ->assertCanRenderTableColumn('created_at')
        ->assertCanRenderTableColumn('actions')
        ->assertSuccessful();
});

it('should render the create page', function () {
    Filament::setCurrentPanel(
        Filament::getPanel('admin'),
    );

    // login with user
    $user = User::factory()->create();

    $this
        ->actingAs($user)
        ->get(ProjectResource::getUrl('create'))
        ->assertSuccessful();
});


it('should render the edit page', function () {
    Filament::setCurrentPanel(
        Filament::getPanel('admin'),
    );

    // login with user
    $user = User::factory()->create();

    $this
        ->actingAs($user)
        ->get(ProjectResource::getUrl('edit', ['record' => Project::factory()->create()]))
        ->assertSuccessful();
});

it('should render the show page', function () {
    Filament::setCurrentPanel(
        Filament::getPanel('admin'),
    );

    // login with user
    $user = User::factory()->create();

    $this
        ->actingAs($user)
        ->get(ProjectResource::getUrl('view', ['record' => Project::factory()->create()]))
        ->assertSuccessful();
});
