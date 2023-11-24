<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Myday extends Model
{
    use HasFactory;
    use SoftDeletes;
     
    protected $fillable = [
        'message',
    ];

    // All query and validation should be in model
    // samples
    //  public function updateData(array $data)
    // {
    //      $validated = $data->validate([
    //                'message' => 'required|string|max:255',
    //            ]);
    //     $this->fill($validated)->save();
    // }

    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comment()
    {
        return $this->hasMany(Comments::class, 'myday_id')->with('author');
    }
}
