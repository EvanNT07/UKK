<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'nama', 'deskripsi', 'harga', 'foto'];

    protected $casts = [
        'harga' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class)->latest();
    }

    public function getFotoUrlAttribute(): ?string
    {
        if (!$this->foto) {
            return null;
        }
        // dukung path lama (products/xxx) maupun URL penuh
        if (str_starts_with($this->foto, 'http')) {
            return $this->foto;
        }
        return Storage::disk('public')->url($this->foto);
    }
}
