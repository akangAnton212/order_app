<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order_DetailT extends Model
{
    use SoftDeletes;
    protected $table = 'tbl_order_detail';
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
        'uid',
        'uid_order',
        'id_product',
        'harga_beli',
        'harga_jual',
        'satuan_besar',
        'qty',
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

    public function supplier() {
        return $this->hasOne('App\Models\ProductM', 'id_product', 'uid');
    }
}
