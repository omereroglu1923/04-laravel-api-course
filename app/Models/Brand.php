<?php

declare(strict_types=1);

namespace App\Models;

use App\Filters\BrandFilters;
use Essa\APIToolKit\Filters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Brand extends Model
{
    use HasFactory, Filterable;

    protected string $default_filters = BrandFilters::class;

    /**
     * Mass-assignable attributes.
     *
     * @var array
     */
    protected $fillable = [
        'name',
		'description',
    ];


}
