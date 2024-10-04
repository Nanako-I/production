<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OptionItem extends Model
{
    use HasFactory;
    protected $table = 'option_items';
    
    protected $fillable = ['people_id','option_id','item1','item2','item3','item4','item5','bikou'];
    
    public function person()
    {
        return $this->belongsTo(Person::class, 'people_id');
    }

    public function option()
    {
        return $this->belongsTo(Option::class, 'option_id');
    }
}
