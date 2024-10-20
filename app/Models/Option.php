<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;
    protected $table = 'options';
    
    protected $fillable = ['title','people_id','facility_id','item1','item2','item3','item4','item5','flag'];
    
    public function person()
    {
        return $this->belongsTo(Person::class, 'people_id');
    }

    public function getItemsAsString()
    {
        $items = [];
        for ($i = 1; $i <= 5; $i++) {
            $itemKey = "item{$i}";
            if (!is_null($this->$itemKey) && $this->$itemKey !== '') {
                $items[] = $this->$itemKey;
            }
        }
        return implode(', ', $items);
    }
    
}
