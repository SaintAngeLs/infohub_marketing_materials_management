<?php

namespace Tests\Unit;

use App\Http\Controllers\Admin\Permissions\PermissionManagementController;
use App\Models\MenuItems\MenuItem;
use App\Models\UsersGroup;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;
use Mockery;
use App\Contracts\IPermissionService;
use App\Contracts\IStatistics;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PermissionManagementControllerTest extends TestCase
{
    use RefreshDatabase;

    public function testUpdateGroupPermission()
    {
        // Create mock objects for the dependencies
        $permissionServiceMock = Mockery::mock(IPermissionService::class);
        $statisticsServiceMock = Mockery::mock(IStatistics::class);

        // Replace the real services with mock services
        $this->app->instance(IPermissionService::class, $permissionServiceMock);
        $this->app->instance(IStatistics::class, $statisticsServiceMock);

        // Seed the database with necessary data
        $group = UsersGroup::factory()->create();
        $user = User::factory()->create(['email_verified_at' => now()]);
        $menuItems = MenuItem::factory()->count(3)->create();

        // Assign ownership of menu items to the user
        foreach ($menuItems as $menuItem) {
            DB::table('menu_owners')->insert([
                'menu_item_id' => $menuItem->id,
                'user_id' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        // Define the request data
        $requestData = [
            'group_id' => $group->id,
            'permissions' => $menuItems->pluck('id')->toArray(),
        ];

        // Authenticate the user
        $this->actingAs($user);

        // Define expectations for the permissionServiceMock
        $permissionServiceMock->shouldReceive('updateGroupPermissions')
            ->once()
            ->with($requestData['group_id'], $requestData['permissions']);

        // Define expectations for the statisticsServiceMock
        $statisticsServiceMock->shouldReceive('logUserActivity')
            ->once()
            ->with($user->id, Mockery::on(function ($data) use ($requestData) {
                return $data['uri'] === 'menu/permissions/update-group-permission' &&
                    $data['post_string'] === json_encode($requestData) &&
                    $data['query_string'] === '';
            }));

        // Call the controller method and get the response
        $response = $this->postJson('/menu/permissions/update-group-permission', $requestData);

        // Debugging the response
        Log::info('Test response:', ['content' => $response->getContent()]);

        // Assert that the response is a JSON response with the correct structure
        $response->assertJson(['message' => 'Group permissions updated successfully.']);

        // Assert the status code
        $response->assertStatus(200);
    }
}
