<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order_HeadT extends Model
{
    use SoftDeletes;
    protected $table = 'tbl_orders';
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
        'id_supplier',
        'po_number',
        'po_time',
        'po_assigne',
        'ro_number',
        'ro_date',
        'ro_assigne',
        'is_approved'
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
        return $this->hasOne('App\Models\SuppliersM', 'uid', 'id_supplier');
    }

    public function order_detail() {
        return $this->hasMany('App\Models\Order_DetailT', 'uid_order', 'uid');
    }
}
