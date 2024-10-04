<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;
    protected $table = 'options';
    
    protected $fillable = ['people_id','title','item1','item2','item3','item4','item5','flag'];
    
    public function person()
    {
        return $this->belongsTo(Person::class, 'people_id');
    }
}
