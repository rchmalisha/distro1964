<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    protected $table = 'materials';
    protected $primaryKey = 'kode_bahan';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'kode_bahan',
        'nama_bahan',
        'kategori_bahan',
        'harga_bahan',
    ];

    public const kategori_bahan = [
        'dtf' => 'DTF',
        'polyflex' => 'Polyflex',
    ];

    // protected static function boot()
    // {
    //     parent::boot();

    //     static::creating(function ($model) {
    //         $last = static::orderBy('kode_bahan', 'desc')->first();
    //         $num = $last ? intval(substr($last->kode_bahan, 3)) + 1 : 1;
    //         $model->kode_bahan = 'MT-' . str_pad($num, 2, '0', STR_PAD_LEFT);
    //     });
    // }
}
