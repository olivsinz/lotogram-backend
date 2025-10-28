<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SiteGroupSite extends Model
{
    use HasFactory;

    protected $fillable = [
        'site_group_id',
        'site_id'
    ];
}
