<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cinema extends Model
{
 protected $fillable = [
        'name',
        'location',
        'owner_company_id',
    
    ];





    public function company()
    {
        return $this->belongsTo(Company::class, 'owner_company_id');
    } 
    
       public function halls()
    {
        return $this->hasMany(Hall::class);
    }
}
