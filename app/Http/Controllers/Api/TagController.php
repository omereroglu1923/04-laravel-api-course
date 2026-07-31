<?php

namespace App\Http\Controllers\Api;

use App\Models\Tag;
use Orion\Concerns\DisableAuthorization;
use Orion\Http\Controllers\Controller;

class TagController extends Controller
{
    use DisableAuthorization; // yetkilendirme kontrolünü kapatıyoruz, çünkü Policy henüz kurmadık

    protected $model = Tag::class;
}
