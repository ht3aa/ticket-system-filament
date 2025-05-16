<?php

use Filament\Facades\Filament;
use App\Filament\Admin\Resources\ProjectResource;
use App\Models\Project;
use App\Models\User;



it('should render the list page', function () {
    Filament::setCurrentPanel(
        Filament::getPanel('admin'),
    );

    // login with user
    $user = User::factory()->create();

    $this
        ->actingAs($user)
        ->get(ProjectResource::getUrl('index'))
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
