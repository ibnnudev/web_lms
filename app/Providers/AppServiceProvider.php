<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(\App\Interfaces\VerificatorInterface::class, \App\Repositories\VerificatorRepository::class);
        $this->app->bind(\App\Interfaces\UserInterface::class, \App\Repositories\UserRepository::class);
        $this->app->bind(\App\Interfaces\CourseCategoryInterface::class, \App\Repositories\CourseCategoryRepository::class);
        $this->app->bind(\App\Interfaces\CourseInterface::class, \App\Repositories\CourseRepository::class);
        $this->app->bind(\App\Interfaces\CourseChapterInterface::class, \App\Repositories\CourseChapterRepository::class);
        $this->app->bind(\App\Interfaces\MinCoursePurchaseAtRegInterface::class, \App\Repositories\MinCoursePurchaseAtRegRepository::class);
        $this->app->bind(\App\Interfaces\QuizInterface::class, \App\Repositories\QuizRepository::class);
        $this->app->bind(\App\Interfaces\QuestionInterface::class, \App\Repositories\QuestionRepository::class);
        $this->app->bind(\App\Interfaces\TransactionInterface::class, \App\Repositories\TransactionRepository::class);

        $this->app->bind(\App\Interfaces\User\UserTransactionInterface::class, \App\Repositories\User\UserTransactionRepository::class);
    }

    public function boot(): void
    {
        //
    }
}
