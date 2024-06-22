<?php

namespace Tests\Unit;

use App\Models\File;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Tests\TestCase;
use App\Contracts\IFileService;
use App\Contracts\IStatistics;
use App\Services\UserService;

class FileControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Mock services
        $this->fileServiceMock = Mockery::mock(IFileService::class);
        $this->statisticsServiceMock = Mockery::mock(IStatistics::class);
        $this->userServiceMock = Mockery::mock(UserService::class);

        // Replace instances in the container with mocks
        $this->app->instance(IFileService::class, $this->fileServiceMock);
        $this->app->instance(IStatistics::class, $this->statisticsServiceMock);
        $this->app->instance(UserService::class, $this->userServiceMock);
    }

    public function testStore()
    {
        Storage::fake('local');

        // Create a user and authenticate
        $user = User::factory()->create();
        $this->actingAs($user);

        $file = UploadedFile::fake()->create('document.pdf', 100);

        // Define request data
        $requestData = [
            'file' => $file,
            'menu_id' => 1,
            'name' => 'Test File',
            'file_source' => 'file_pc'
        ];

        // Mock service methods
//        $this->fileServiceMock->shouldReceive('validateRequest')->once()->andReturn($requestData);
//        $this->fileServiceMock->shouldReceive('handleFileUpload')->once();
//        $this->fileServiceMock->shouldReceive('updateFileModel')->once();
//        $this->fileServiceMock->shouldReceive('detectFileChanges')->once()->andReturn(true);

//        $this->statisticsServiceMock->shouldReceive('logUserActivity')->once();
//        $this->userServiceMock->shouldReceive('notifyUserAboutFileChange')->once();

        // Make POST request
        $response = $this->post(route('menu.files.store'), $requestData);

        // Assertions
        $response->assertStatus(302);
    }


    public function testUpdate()
    {
        Storage::fake('local');

        // Create a user and authenticate
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create a file in the database
        $file = File::factory()->create();

        $updatedFile = UploadedFile::fake()->create('updated_document.pdf', 100);

        // Define request data
        $requestData = [
            'file' => $updatedFile,
            'menu_id' => $file->menu_id,
        ];

        // Mock service methods
//        $this->fileServiceMock->shouldReceive('validateRequest')->once()->andReturn($requestData);
//        $this->fileServiceMock->shouldReceive('handleFileUpload')->once();
//        $this->fileServiceMock->shouldReceive('updateFileModel')->once();
//        $this->fileServiceMock->shouldReceive('detectFileChanges')->once()->andReturn(true);
//
//        $this->statisticsServiceMock->shouldReceive('logUserActivity')->once();
//        $this->userServiceMock->shouldReceive('notifyUserAboutFileChange')->once();

        // Make PATCH request
        $response = $this->patch(route('menu.file.update', $file->id), $requestData);

        // Assertions
        $response->assertStatus(302);
    }

    public function testDeleteFile()
    {
        // Create a user and authenticate
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create a file in the database
        $file = File::factory()->create();

        // Mock service methods
//        $this->fileServiceMock->shouldReceive('deleteFile')->once();

        // Make DELETE request
        $response = $this->delete(route('menu.files.delete', $file->id));

        // Assertions
        $response->assertStatus(302);
    }

    public function testDownload()
    {
        Storage::fake('local');

        // Create a user and authenticate
        $user = User::factory()->create();
        $this->actingAs($user);

        // Create a file in the database
        $file = File::factory()->create(['path' => 'logs/laravel.log']);

        // Ensure the file exists in the fake storage
        Storage::put('public/logs/laravel.log', 'Log Contents');

        // Mock service methods
        $this->fileServiceMock->shouldReceive('downloadFile')->once()->andReturn(response()->download(storage_path('app/public/logs/laravel.log')));

        // Make GET request
        $response = $this->get(route('files.download', $file->id));

        // Assertions
        $response->assertStatus(200);
    }


    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
