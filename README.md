<img src="img/banner.png" alt="Logo">

[![Latest Version on Packagist](https://img.shields.io/packagist/v/timwassenburg/laravel-service-generator.svg?style=flat-square)](https://packagist.org/packages/timwassenburg/laravel-service-generator)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/timwassenburg/laravel-service-generator/run-tests.yml?branch=master&label=tests&style=flat-square)](https://github.com/timwassenburg/laravel-service-generator/actions?query=workflow%3Arun-tests+branch%3Amaster)
[![Total Downloads](https://img.shields.io/packagist/dt/timwassenburg/laravel-service-generator.svg?style=flat-square)](https://packagist.org/packages/timwassenburg/laravel-service-generator)
[![License](https://img.shields.io/packagist/l/timwassenburg/laravel-service-generator)](https://packagist.org/packages/timwassenburg/laravel-service-generator)

<hr>

## Table of Contents
  <ol>
    <li><a href="#features">Features</a></li>
    <li><a href="#installation">Installation</a></li>
    <li>
      <a href="#usage">Usage</a>
      <ul>
        <li><a href="#generate-services">Generate services</a></li>
        <li><a href="#generate-services-for-models">Generate services for models</a></li>
        <li><a href="#generate-services-for-controllers">Generate services for controllers</a></li>
      </ul>
    </li>
    <li>
        <a href="#the-service-pattern">The service pattern</a>
        <ul>
            <li><a href="#when-to-use-the-service-pattern">When to use the service pattern</a></li>
            <li>
                <a href="#how-to-use-services">How to use services</a>
                <ul>
                    <li><a href="#static-methods">Static methods</a></li>
                    <li><a href="#depency-injection">Dependency Injection</a></li>
                </ul>
            </li>
        </ul>
    </li>
    <li><a href="#testing">Testing</a></li>
    <li><a href="#more-generator-packages">More generator packages</a></li>
    <li><a href="#contributing">Contributing</a></li>
    <li><a href="#license">License</a></li>
  </ol>

## Features

This package adds the ```php artisan make:service {name}``` command. The command
generates an empty service class in app\Services to get quickly started.

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

Optionally, you can add multiple method names (seperated by comma) with the ```--methods``` param.

```bash
php artisan make:service UserService --methods=register,login,logout
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

## The service pattern

### When to use the service pattern

A common question is: where do I put my business logic? You want to keep your models thin and your controller functions
skinny. There are multiple ways to archive this, extracting your business logic to the
service layer is a common method. By encapsulating your business logic in a service class you
are able to re-use the logic for example in your controllers, commands, jobs and middelware.

### How to use services

Once you have made a service it is time to add your business logic. We will discus how to use a service via static
methods,
dependency injection and how to use it with interfaces and repositories.

#### Static methods

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
}#
```

The ```getPostBySlug``` method is in this example a very simple function but as you can see it keeps you controller
skinny
and and your business logic seperated. Keep in mind that static classes and methods are stateless. The class won't save
any data in itself.

#### Dependency Injection

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

## Testing

Run the tests with:

```bash
composer test
```

## More generator packages

Looking for more ways to speed up your workflow? Make sure to check out these packages.

- [Laravel Action Generator](https://github.com/timwassenburg/laravel-action-generator)
- [Laravel Pivot Table Generator](https://github.com/timwassenburg/laravel-pivot-table-generator)
- [Laravel Repository Generator](https://github.com/timwassenburg/laravel-repository-generator)
- [Laravel Service Generator](https://github.com/timwassenburg/laravel-service-generator)
- [Laravel Trait Generator](https://github.com/timwassenburg/laravel-trait-generator)

The packages mentioned above are part of [Laravel Artisan Extender](https://github.com/timwassenburg/laravel-artisan-extender).

## Contributing

Contributions are what make the open source community such an amazing place to learn, inspire, and create. Any
contributions you make are **greatly appreciated**.

If you have a suggestion that would make this better, please fork the repo and create a pull request. You can also
simply open an issue with the tag "enhancement".
Don't forget to give the project a star! Thanks again!

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
