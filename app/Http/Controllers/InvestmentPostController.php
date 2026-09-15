<?php

namespace App\Http\Controllers;

use App\Helpers\ImageHelper;
use App\Models\InvestmentPost;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InvestmentPostController extends Controller
{
    /**
     * Display a listing of the investment posts.
     */
    public function index(Request $request): View
    {
        $query = InvestmentPost::latest();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $posts = $query->paginate(10)->withQueryString();

        return view('backend.posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new investment post.
     */
    public function create(): View
    {
        return view('backend.posts.create');
    }

    /**
     * Store a newly created investment post in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'total_quantity' => 'required|numeric|min:1',
            'unit_cost' => 'required|numeric|min:0.01',
            'profit_per_unit' => 'required|numeric|min:0',
            'expected_import_days' => 'required|integer|min:1',
            'target_amount' => 'nullable|numeric|min:0.01',
            'min_investment_amount' => 'nullable|numeric|min:1',
            'status' => 'required|in:active,upcoming,imported,sold_out,completed',
            'type' => 'required|in:Import,Local,Manufacture',
            'msg_profit_payment' => 'nullable|string|max:255',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
        ]);

        if (empty($validated['target_amount'])) {
            $validated['target_amount'] = $validated['total_quantity'] * $validated['unit_cost'];
        }

        if (empty($validated['min_investment_amount'])) {
            $validated['min_investment_amount'] = $validated['target_amount'] * 0.8;
        }

        if ($request->hasFile('image')) {
            $validated['image'] = ImageHelper::uploadImage($request->file('image'), 'uploads/posts');
        }

        $galleryPaths = [];
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $gFile) {
                if ($gFile) {
                    $galleryPaths[] = ImageHelper::uploadImage($gFile, 'uploads/posts/gallery');
                }
            }
        }
        $validated['gallery_images'] = $galleryPaths;

        InvestmentPost::create($validated);

        return redirect()->route('posts.index')->with('success', 'Investment post created successfully.');
    }

    /**
     * Show the form for editing the specified investment post.
     */
    public function edit(InvestmentPost $post): View
    {
        return view('backend.posts.edit', compact('post'));
    }

    /**
     * Update the specified investment post in storage.
     */
    public function update(Request $request, InvestmentPost $post): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'gallery_images' => 'nullable|array',
            'gallery_images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'remove_gallery_images' => 'nullable|array',
            'total_quantity' => 'required|numeric|min:1',
            'unit_cost' => 'required|numeric|min:0.01',
            'profit_per_unit' => 'required|numeric|min:0',
            'expected_import_days' => 'required|integer|min:1',
            'target_amount' => 'nullable|numeric|min:0.01',
            'min_investment_amount' => 'nullable|numeric|min:1',
            'status' => 'required|in:active,upcoming,imported,sold_out,completed',
            'type' => 'required|in:Import,Local,Manufacture',
            'msg_profit_payment' => 'nullable|string|max:255',
            'starts_at' => 'nullable|date',
            'ends_at' => 'nullable|date|after_or_equal:starts_at',
        ]);

        if (empty($validated['target_amount'])) {
            $validated['target_amount'] = $validated['total_quantity'] * $validated['unit_cost'];
        }

        if (empty($validated['min_investment_amount'])) {
            $validated['min_investment_amount'] = $validated['target_amount'] * 0.8;
        }

        if ($request->hasFile('image')) {
            $validated['image'] = ImageHelper::uploadImage($request->file('image'), 'uploads/posts', $post->image);
        }

        $currentGallery = is_array($post->gallery_images) ? $post->gallery_images : [];

        // Handle removals
        if ($request->filled('remove_gallery_images')) {
            foreach ($request->remove_gallery_images as $remPath) {
                ImageHelper::deleteImage($remPath);
                $currentGallery = array_values(array_filter($currentGallery, fn($p) => $p !== $remPath));
            }
        }

        // Handle new gallery uploads
        if ($request->hasFile('gallery_images')) {
            foreach ($request->file('gallery_images') as $gFile) {
                if ($gFile) {
                    $currentGallery[] = ImageHelper::uploadImage($gFile, 'uploads/posts/gallery');
                }
            }
        }

        $validated['gallery_images'] = $currentGallery;

        $post->update($validated);

        return redirect()->route('posts.index')->with('success', 'Investment post updated successfully.');
    }

    /**
     * Remove the specified investment post from storage.
     */
    public function destroy(InvestmentPost $post): RedirectResponse
    {
        if ($post->image) {
            ImageHelper::deleteImage($post->image);
        }

        if (is_array($post->gallery_images)) {
            foreach ($post->gallery_images as $gPath) {
                ImageHelper::deleteImage($gPath);
            }
        }

        $post->delete();

        return redirect()->route('posts.index')->with('success', 'Investment post deleted successfully.');
    }
}
