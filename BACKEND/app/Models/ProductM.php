<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductM extends Model
{
    use SoftDeletes;
    protected $table = 'tbl_product';
    protected $dates =['deleted_at'];
    protected $softDelete = true;
    public $timestamps = true;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'id_producttype',
        'id_ncc',
        'SKU',
        'description',
        'brand',
        'uid'
    ];

    protected $primaryKey = 'id';

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    public static function boot() {
        parent::boot();
        // Listen creating event
        self::creating(function ($model) {
            $model->uid = (string) Str::uuid();
        });
    }

    public function product_type() {
        return $this->hasOne('App\Models\ProductTypeM', 'uid', 'id_producttype');
    }

    public function ncc() {
        return $this->hasOne('App\Models\NccM', 'uid', 'id_ncc');
    }
}
