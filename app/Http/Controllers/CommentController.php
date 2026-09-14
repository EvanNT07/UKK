<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Product;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    // User & Admin boleh menambahkan komentar (sesuai tabel fitur UKK)
    public function store(Request $request, Product $product)
    {
        $data = $request->validate([
            'komentar' => 'required|string|max:1000',
        ], [
            'komentar.required' => 'Komentar wajib diisi.',
            'komentar.max' => 'Komentar maksimal 1000 karakter.',
        ]);

        Comment::create([
            'product_id' => $product->id,
            'user_id' => auth()->id(),
            'komentar' => $data['komentar'],
        ]);

        return back()->with('success', 'Komentar berhasil ditambahkan!');
    }

    public function destroy(Comment $comment)
    {
        $user = auth()->user();
        // Admin boleh hapus semua, user hanya boleh hapus miliknya
        if ($user->role !== 'admin' && $comment->user_id !== $user->id) {
            abort(403, 'Anda tidak boleh menghapus komentar ini.');
        }

        $comment->delete();
        return back()->with('success', 'Komentar berhasil dihapus!');
    }
}
