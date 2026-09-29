<?php

namespace App\Http\Controllers;

use App\Helpers\ImageHelper;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PageContentController extends Controller
{
    /**
     * Show the page content settings dashboard.
     */
    public function index(Request $request): View
    {
        $slug = $request->query('slug', 'home');
        $page = Page::getPage($slug);
        $content = $page->content ?? Page::defaultContentFor($slug);

        return view('backend.pages.index', compact('page', 'slug', 'content'));
    }

    /**
     * Update page content.
     */
    public function update(Request $request): RedirectResponse
    {
        $slug = $request->input('slug', 'home');
        $page = Page::getPage($slug);
        $existingContent = $page->content ?? Page::defaultContentFor($slug);

        $request->validate([
            'slug' => 'required|string',
            'hero_image' => 'nullable|image|mimes:jpeg,jpg,png,webp,svg|max:4096',
            'hero_video_url' => 'nullable|string|max:1000',
        ]);

        $inputContent = $request->input('content', []);

        // Process Hero Image Upload
        if ($request->hasFile('hero_image')) {
            $oldImage = $existingContent['hero']['image'] ?? null;
            $newImage = ImageHelper::uploadImage($request->file('hero_image'), 'uploads/pages', $oldImage);
            $inputContent['hero']['image'] = $newImage;
        } else {
            // Retain old image if no new file uploaded
            if (!isset($inputContent['hero']['image']) && isset($existingContent['hero']['image'])) {
                $inputContent['hero']['image'] = $existingContent['hero']['image'];
            }
        }

        // Handle check-boxes / toggles that may not send a value when unchecked
        $inputContent['hero']['show_badge'] = $request->has('content.hero.show_badge');
        $inputContent['features']['show_section'] = $request->has('content.features.show_section');
        $inputContent['how_it_works']['show_section'] = $request->has('content.how_it_works.show_section');
        $inputContent['why_invest']['show_section'] = $request->has('content.why_invest.show_section');
        $inputContent['cta']['show_section'] = $request->has('content.cta.show_section');

        // Clean re-indexed arrays for repeatable items
        if (isset($inputContent['hero']['trust_badges']) && is_array($inputContent['hero']['trust_badges'])) {
            $inputContent['hero']['trust_badges'] = array_values(array_filter($inputContent['hero']['trust_badges'], function ($item) {
                return !empty($item['title']) || !empty($item['icon']);
            }));
        }

        if (isset($inputContent['features']['items']) && is_array($inputContent['features']['items'])) {
            $inputContent['features']['items'] = array_values(array_filter($inputContent['features']['items'], function ($item) {
                return !empty($item['title']) || !empty($item['desc']);
            }));
        }

        if (isset($inputContent['how_it_works']['steps']) && is_array($inputContent['how_it_works']['steps'])) {
            $inputContent['how_it_works']['steps'] = array_values(array_filter($inputContent['how_it_works']['steps'], function ($item) {
                return !empty($item['title']) || !empty($item['num']);
            }));
        }

        if (isset($inputContent['why_invest']['cards']) && is_array($inputContent['why_invest']['cards'])) {
            $inputContent['why_invest']['cards'] = array_values(array_filter($inputContent['why_invest']['cards'], function ($item) {
                return !empty($item['title']) || !empty($item['desc']);
            }));
        }

        // Merge updated content with default structure so no structural field is lost
        $finalContent = array_replace_recursive($existingContent, $inputContent);

        $page->content = $finalContent;
        if ($request->filled('page_title')) {
            $page->title = $request->input('page_title');
        }
        $page->save();

        return redirect()->route('settings.pages-content.index', ['slug' => $slug])
            ->with('success', ucfirst($slug) . ' page content updated successfully!');
    }

    /**
     * Reset page content back to default.
     */
    public function reset(Request $request): RedirectResponse
    {
        $slug = $request->input('slug', 'home');
        $page = Page::getPage($slug);
        $page->content = Page::defaultContentFor($slug);
        $page->save();

        return redirect()->route('settings.pages-content.index', ['slug' => $slug])
            ->with('success', ucfirst($slug) . ' page content reset to defaults.');
    }
}

