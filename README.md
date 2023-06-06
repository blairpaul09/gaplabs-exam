
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

