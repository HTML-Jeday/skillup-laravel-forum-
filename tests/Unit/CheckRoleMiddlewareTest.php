<?php

namespace Tests\Unit;

use App\Http\Middleware\CheckRole;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class CheckRoleMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test middleware allows access to user with required role
     *
     * @return void
     */
    public function testAllowsAccessToUserWithRequiredRole()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $this->actingAs($user);
        
        $request = Request::create('/admin/dashboard', 'GET');
        $middleware = new CheckRole();
        
        $response = $middleware->handle($request, function () {
            return new Response('Allowed');
        }, 'admin');
        
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Allowed', $response->getContent());
    }

    /**
     * Test middleware allows access to user with one of multiple required roles
     *
     * @return void
     */
    public function testAllowsAccessToUserWithOneOfMultipleRequiredRoles()
    {
        $user = User::factory()->create(['role' => 'moderator']);
        $this->actingAs($user);
        
        $request = Request::create('/admin/dashboard', 'GET');
        $middleware = new CheckRole();
        
        $response = $middleware->handle($request, function () {
            return new Response('Allowed');
        }, 'admin', 'moderator');
        
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('Allowed', $response->getContent());
    }

    /**
     * Test middleware denies access to user without required role
     *
     * @return void
     */
    public function testDeniesAccessToUserWithoutRequiredRole()
    {
        $user = User::factory()->create(['role' => 'user']);
        $this->actingAs($user);
        
        $request = Request::create('/admin/dashboard', 'GET');
        $middleware = new CheckRole();
        
        try {
            $middleware->handle($request, function () {
                return new Response('Allowed');
            }, 'admin');
            $this->fail('Expected HttpException was not thrown');
        } catch (HttpException $e) {
            $this->assertEquals(403, $e->getStatusCode());
        }
    }

    /**
     * Test middleware denies access to guest
     *
     * @return void
     */
    public function testDeniesAccessToGuest()
    {
        $request = Request::create('/admin/dashboard', 'GET');
        $middleware = new CheckRole();
        
        try {
            $middleware->handle($request, function () {
                return new Response('Allowed');
            }, 'admin');
            $this->fail('Expected HttpException was not thrown');
        } catch (HttpException $e) {
            $this->assertEquals(403, $e->getStatusCode());
        }
    }
}
