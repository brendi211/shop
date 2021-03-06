<?php

namespace App\Models;

use App\Traits\Models\Image;
use App\Traits\Models\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

final class ProductCollection extends Model
{
    use SoftDeletes;
    use Translatable;
    use Image;

    public $translate = [
        'name',
        'description',
        'meta_title',
        'meta_keywords',
        'meta_description',
    ];

    protected $fillable = [
        'parent_id',
        'name_uk',
        'name_ru',
        'slug',
        'meta_title_uk',
        'meta_title_ru',
        'meta_keywords_uk',
        'meta_keywords_ru',
        'meta_description_uk',
        'meta_description_ru',
        'image'
    ];

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    public $timestamps = true;

    protected $table = 'collections';

    public static function getTableName(): string
    {
        return (new static)->table;
    }

    public function getUrlAttribute(): string
    {
        return route('collection', $this->slug);
    }

    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'collection_products', 'collection_id');
    }

    public function scopeRoot(Builder $builder): void
    {
        $builder->where('parent_id', 0);
    }

    public function child(): HasMany
    {
        return $this->hasMany(ProductCollection::class, 'parent_id', 'id');
    }

    public function parent(): HasOne
    {
        return $this->hasOne(ProductCollection::class, 'id', 'parent_id');
    }
}
