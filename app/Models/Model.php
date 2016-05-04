<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model as BaseModel;

abstract class Model extends BaseModel
{
    /**
     * @var array
     */
    public $timestamps = [];

    /**
     * @var array
     */
    protected $guarded = [];
}
