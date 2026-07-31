<?php

namespace App\Http\Controllers\Api;

use App\Models\Category; // senin gerçek Category modelin
use Orion\Concerns\DisableAuthorization;
use Orion\Http\Controllers\Controller;

class OrionCategoryController extends Controller
{
    use DisableAuthorization;

    protected $model = Category::class; // Tag yerine artık senin gerçek modelin
}
