# Laravel Service Generator

This simple package adds the ```php artisan make:service {name}``` command. The command 
generates an empty service class in ```app\Services``` to get started. I made this mainly
for own use because I like to be able to generate recurring files from the command line to keep
my workflow consistent.

## Installation
Install the package with composer.
```bash
composer require timwassenburg/laravel-service-generator --dev
```

## Usage
After installation the ```php artisan make:service {name}``` will be available in the list
of artisan commands. 

### Generate Service
To generate a new service use the following artisan command.
```bash
php artisan make:service UserService
```

### Generate a service for a model
Add a ```--service``` or ```-S``` param to generate a service for the model.
```bash
php artisan make:model Post --service
```

Use the ```-a``` or ```--all``` param to generate a service, migration, seeder, factory, policy, 
and resource controller for the model.
```bash
php artisan make:model Post --all
```

### Generate a service for a controller
Add a ```--service``` or ```-S``` param to generate a service for the controller.

```bash
php artisan make:controller PostController --service
```

## When to use the service pattern
A common question is: where do I put my business logic? You want to keep your models thin and your controller functions 
skinny. There are multiple ways to archive this, extracting your business logic to the
service layer is a common method. By encapsulating your business logic in a service class you
are able to re-use the logic for example in your controllers, commands, jobs and middelware.

## How to use services
Once you have made a service it is time to add your business logic. We will discus how to use a service via static methods,
dependency injection and how to use it with interfaces and repositories.

### Static methods
a common way to use a service is to call it's methods statically. It is similar to helper functions. Let's say we have
a ```PostService``` with a method to get a post based on a slug.

```php
namespace App\Services;

use App\Models\Post;

class PostService
{
    // Declare the function as static
    public static function getPostBySlug(string $slug): Post
    {
        return Post::with('tags')
            ->where('slug', $slug)
            ->get();
    }
}
```

Next you can include the service class for example your controller and call the ```getPostBySlug``` method statically.
```php
namespace App\Http\Controllers;

// Include the service
use App\Services\PostService;

class PostController extends Controller
{
    public function show(string $slug)
    {
        // Call the method statically from the service class
        $post = PostService::getPostBySlug($slug);
        
        return view('posts.show', compact('post'));
    }
}
```

The ```getPostBySlug``` method is in this example a very simple function but as you can see it keeps you controller skinny
and and your business logic seperated. Keep in mind that static classes and methods are stateless. The class won't save 
any data in itself.

### Dependency Injection
Another popular method is to use services with dependency injection. With dependency injection you can write loosely 
coupled code. When done right this will improve the flexibility and maintainability of your code.

The ```PostService``` we used as example before will remain
almost the same except we don't declare the functions inside the class as static anymore.

```php
namespace App\Services;

use App\Models\Post;

class PostService
{
    public function getPostBySlug(string $slug): Post
    {
        return Post::with('tags')
            ->where('slug', $slug)
            ->get();
    }
}
```

Next we inject the service into the constructor of the class where we want to use it. Inside the constructor we
assign the object to the ```$postService``` class property. Now the ```$postService``` property will be callable in 
all functions within the class with ```$this->postService```. While typing your IDE will already typehint the functions
in your PostService class, in this case only ```->getPostBySlug($slug)```. 
```php
namespace App\Http\Controllers;

// Include the service
use App\Services\PostService;

class PostController extends Controller
{
    // Declare the property
    protected $postService;

    // Inject the service into the constructor
    public function __construct(PostService $postService)
    {
        // Assign the service instance to the class property
        $this->postService = $postService;
    }

    public function show($slug)
    {
        // Call the method you need from the service via the class property
        $post = $this->postService->getPostBySlug($slug);
        
        return view('posts.show', compact('post'));
    }
}
```



## Contributing
If you want to contribute to this package feel tree to open a ticket or a pull request. 

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
