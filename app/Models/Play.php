<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Play extends Model
{
    protected $guarded = [
        '_token',
    ];

    public function getCategoryByKey($key)
    {
        return $this->where('key', $key)->first() ? $this->where('key', $key)->first()->category : null;
    }
}