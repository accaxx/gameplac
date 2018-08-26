<?php
namespace App\Models\Othello;

use Illuminate\Database\Eloquent\Model;

class Othello extends Model
{
    protected $guarded = [
        '_token',
    ];
}