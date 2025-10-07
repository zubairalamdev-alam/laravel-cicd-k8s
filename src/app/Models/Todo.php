<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Todo extends Model
{
    use HasFactory;
    protected $fillable = ['user_id','title','completed'];
    protected $casts = ['completed' => 'boolean'];
}
