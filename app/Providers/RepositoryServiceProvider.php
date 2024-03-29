<?php


namespace App\Providers;


use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(
            'App\Interfaces\PostInterface',
            'App\Repositories\PostRepository'
        );

        $this->app->bind(
            'App\Interfaces\CommentInterface',
            'App\Repositories\CommentRepository'
        );

        $this->app->bind(
            'App\Interfaces\CategoryInterface',
            'App\Repositories\CategoryRepository'
        );

        $this->app->bind(
            'App\Interfaces\LabelInterface',
            'App\Repositories\LabelRepository'
        );
    }

}
