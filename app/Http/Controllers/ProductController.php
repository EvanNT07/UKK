<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    // ============ AREA PUBLIK / USER : Lihat Foto Produk ============
    public function index(Request $request)
    {
        $search = $request->query('q');

        $products = Product::with(['user'])
            ->withCount('comments')
            ->when($search, function ($query) use ($search) {
                $query->where('nama', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('katalog.index', compact('products', 'search'));
    }

    public function show(Product $product)
    {
        $product->load(['user', 'comments.user']);
        $related = Product::where('id', '!=', $product->id)->latest()->take(4)->get();
        return view('katalog.show', compact('product', 'related'));
    }

    // ============ AREA ADMIN ============
    public function dashboard()
    {
        $totalProduk = Product::count();
        $totalUser = User::where('role', 'user')->count();
        $totalKomentar = Comment::count();
        $produkTerbaru = Product::withCount('comments')->latest()->take(5)->get();
        $komentarTerbaru = Comment::with(['user', 'product'])->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalProduk', 'totalUser', 'totalKomentar', 'produkTerbaru', 'komentarTerbaru'
        ));
    }

    public function adminIndex(Request $request)
    {
        $search = $request->query('q');
        $products = Product::withCount('comments')
            ->when($search, fn ($q) => $q->where('nama', 'like', "%{$search}%"))
            ->latest()->paginate(10)->withQueryString();

        return view('admin.produk.index', compact('products', 'search'));
    }

    public function create()
    {
        return view('admin.produk.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'foto' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
        ], [
            'nama.required' => 'Nama produk wajib diisi.',
            'harga.required' => 'Harga wajib diisi.',
            'harga.numeric' => 'Harga harus berupa angka.',
            'foto.required' => 'Foto produk wajib diupload.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.max' => 'Ukuran foto maksimal 2MB.',
        ]);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('products', 'public');
        }
        $data['user_id'] = auth()->id();

        Product::create($data);

        return redirect()->route('admin.produk.index')->with('success', 'Foto produk berhasil ditambahkan!');
    }

    public function edit(Product $product)
    {
        return view('admin.produk.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'harga' => 'required|numeric|min:0',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        if ($request->hasFile('foto')) {
            // hapus foto lama
            if ($product->foto && Storage::disk('public')->exists($product->foto)) {
                Storage::disk('public')->delete($product->foto);
            }
            $data['foto'] = $request->file('foto')->store('products', 'public');
        } else {
            unset($data['foto']);
        }

        $product->update($data);

        return redirect()->route('admin.produk.index')->with('success', 'Foto produk berhasil diupdate!');
    }

    public function destroy(Product $product)
    {
        if ($product->foto && Storage::disk('public')->exists($product->foto)) {
            Storage::disk('public')->delete($product->foto);
        }
        $product->delete();

        return redirect()->route('admin.produk.index')->with('success', 'Foto produk berhasil dihapus!');
    }
}
