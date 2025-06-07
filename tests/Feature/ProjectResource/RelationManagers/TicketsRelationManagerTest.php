<?php

use App\Filament\Admin\Resources\ProjectResource;
use App\Filament\Admin\Resources\ProjectResource\Pages\EditProject;
use App\Models\Project;
use App\Models\Ticket;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Livewire\livewire;

uses(RefreshDatabase::class);

beforeEach(function () {
    Filament::setCurrentPanel(
        Filament::getPanel('admin'),
    );
});

it('should render the tickets relation manager', function () {
    $project = Project::factory()->create();

    livewire(ProjectResource\RelationManagers\TicketsRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => EditProject::class,
    ])->assertSuccessful();
});

it('should list project tickets', function () {
    $project = Project::factory()->create();
    $tickets = Ticket::factory()->count(3)->create([
        'project_id' => $project->id,
    ]);

    livewire(ProjectResource\RelationManagers\TicketsRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => EditProject::class,
    ])
        ->assertCanSeeTableRecords($tickets);
});

it('should create a project ticket', function () {
    $project = Project::factory()->create();
    $ticket = Ticket::factory()->make();

    livewire(ProjectResource\RelationManagers\TicketsRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => EditProject::class,
    ])
        ->fillForm([
            'title' => $ticket->title,
            'description' => $ticket->description,
            'code' => $ticket->code,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Ticket::class, [
        'title' => $ticket->title,
        'description' => $ticket->description,
        'code' => $ticket->code,
        'project_id' => $project->id,
    ]);
});

it('should validate that the title is required when creating a ticket', function () {
    $project = Project::factory()->create();

    livewire(ProjectResource\RelationManagers\TicketsRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => EditProject::class,
    ])
        ->fillForm([
            'title' => null,
            'description' => 'Test description',
            'code' => 'TEST-1',
        ])
        ->call('create')
        ->assertHasFormErrors(['title' => 'required']);
});

it('should validate that the code is required when creating a ticket', function () {
    $project = Project::factory()->create();

    livewire(ProjectResource\RelationManagers\TicketsRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => EditProject::class,
    ])
        ->fillForm([
            'title' => 'Test Ticket',
            'description' => 'Test description',
            'code' => null,
        ])
        ->call('create')
        ->assertHasFormErrors(['code' => 'required']);
});

it('should validate that the code is unique within the project when creating a ticket', function () {
    $project = Project::factory()->create();
    $existingTicket = Ticket::factory()->create([
        'project_id' => $project->id,
    ]);

    livewire(ProjectResource\RelationManagers\TicketsRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => EditProject::class,
    ])
        ->fillForm([
            'title' => 'Test Ticket',
            'description' => 'Test description',
            'code' => $existingTicket->code,
        ])
        ->call('create')
        ->assertHasFormErrors(['code' => 'unique']);
});

it('should edit a project ticket', function () {
    $project = Project::factory()->create();
    $ticket = Ticket::factory()->create([
        'project_id' => $project->id,
    ]);
    $newData = Ticket::factory()->make();

    livewire(ProjectResource\RelationManagers\TicketsRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => EditProject::class,
    ])
        ->fillForm([
            'title' => $newData->title,
            'description' => $newData->description,
            'code' => $newData->code,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $this->assertDatabaseHas(Ticket::class, [
        'id' => $ticket->id,
        'title' => $newData->title,
        'description' => $newData->description,
        'code' => $newData->code,
        'project_id' => $project->id,
    ]);
});

it('should delete a project ticket', function () {
    $project = Project::factory()->create();
    $ticket = Ticket::factory()->create([
        'project_id' => $project->id,
    ]);

    livewire(ProjectResource\RelationManagers\TicketsRelationManager::class, [
        'ownerRecord' => $project,
        'pageClass' => EditProject::class,
    ])
        ->callTableAction('delete', $ticket);

    $this->assertSoftDeleted($ticket);
});
