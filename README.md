
## Development tools

- **[composer](https://getcomposer.org/)**

## PHP Dependencies

- PHP >= 8.1
- Ctype PHP Extension
- cURL PHP Extension
- DOM PHP Extension
- Fileinfo PHP Extension
- Filter PHP Extension
- Hash PHP Extension
- Mbstring PHP Extension
- OpenSSL PHP Extension
- PCRE PHP Extension
- PDO PHP Extension
- Session PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension

## Libraries

- **[tymon/jwt-auth](https://jwt-auth.readthedocs.io/en/develop/laravel-installation/)**
- **[spatie/laravel-permission](https://spatie.be/docs/laravel-permission/v5/introduction/)**

## Run application locally

1. run this command. "composer install"
2. create .env file and copy the contents of .env.example file.
3. configure the database settings from the created .env file.
4. generate an application key by running this command. "php artisan key:generate"
5. run migration by running this command. "php artisan migrate".
6. run seeder to create roles and a super admin.
7. Finally, serve the application by running "php artisan serve"

## Super admin credentials
username: superadmin.gaplabs
password: password


## Middleware
This middleware is to check whether the user has the right role to perform an action.

```
<?php

namespace App\Http\Middleware;

use App\Exceptions\ForbiddenException;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HasRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        $roles = explode(',', $roles);

        $user = $request->user();

        foreach ($roles as $role) {
            if(!$user->hasRole($role)){
                throw new ForbiddenException();
            }
        }

        return $next($request);
    }
}
```

This is being registered to the Application Http Kernel, under $middlewareAliases property.

```
<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{

    /**
     * The application's middleware aliases.
     *
     * Aliases may be used instead of class names to conveniently assign middleware to routes and groups.
     *
     * @var array<string, class-string|string>
     */
    protected $middlewareAliases = [
        'has_role' => \App\Http\Middleware\HasRoleMiddleware::class
    ];
}
```

## Middleware Usage

You have two approaches to integrate the middleware. One is by using it to Controller and the other approach is to use it in the Routes.

- Note: make sure to follow the pattern of calling this middleware. "has_role:admin,super_admin,user" 

1. From controller

```
<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignUserRoleRequest;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Response;

class UserController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreUserRequest $request
     * @return \App\Http\Resources\UserResource
     */
    public function store(StoreUserRequest $request) : UserResource
    {
        $this->middleware('has_role:'.Role::SUPER_ADMIN->value.','.Role::ADMIN->value)
        //logics
    }
}
```

2. From Routes

```
<?php

use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;
use App\Enums\Role;
use App\Http\Controllers\Api\UserController;

Route::post('/login', [AuthController::class, 'login']);

Route::group(['middleware' => 'auth'], function(){
    Route::get('me', [AuthController::class, 'me']);
    Route::post('logout', [AuthController::class, 'logout']);

    //users Rest API
    Route::prefix('users')
        ->controller(UserController::class)
        ->group(function () {
            Route::get('/', 'index');
            Route::post('/', 'store')->middleware('has_role:'.Role::SUPER_ADMIN->value.','.Role::ADMIN->value);
            Route::put('/{id}', 'update')->middleware('has_role:'.Role::SUPER_ADMIN->value.','.Role::ADMIN->value);
            Route::delete('/{id}', 'destroy')->middleware('has_role:'.Role::SUPER_ADMIN->value);
            Route::post('/{id}/assign-role', 'assignRole')->middleware('has_role:'.Role::SUPER_ADMIN->value);
        });
});
```
