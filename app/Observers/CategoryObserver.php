<?php

namespace App\Observers;

use App\Models\Category;

class CategoryObserver
{
    public function created(Category $category): void
    {
        cache()->forget('categories');
    }

    public function updated(Category $category): void
    {
        cache()->forget('categories');
    }

    public function deleted(Category $category): void
    {
        cache()->forget('categories');
    }
}
