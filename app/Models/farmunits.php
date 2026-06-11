<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class farmunits extends Model
{
     //Hold the Basic details of the Farm plots
     protected $fillable = [
        'farmid',
        'id',
        'fuarea',
        'fulatitude',
        'fulongitude',
        'plot_coords',
        'plotname',
        'estimatedyield',
        'actualyeild',
        'active', 'farmentranceid', 'imagefilepath', 'season','dateplanted'
     ];

   public function farmentrance(){
        return $this->hasOne(farmentrance::class, 'id', 'farmentranceid');
    }
    
}
