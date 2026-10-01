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
     * Resolve checkbox toggle values from form submissions, properly handling unchecked checkboxes.
     */
    private function resolveToggle(Request $request, string $field): bool
    {
        return $request->boolean($field);
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
            'story_image' => 'nullable|image|mimes:jpeg,jpg,png,webp,svg|max:4096',
        ]);

        $inputContent = $request->input('content', []);

        // Process Hero Image Upload
        if ($request->hasFile('hero_image')) {
            $oldImage = $existingContent['hero']['image'] ?? null;
            $newImage = ImageHelper::uploadImage($request->file('hero_image'), 'uploads/pages', $oldImage);
            $inputContent['hero']['image'] = $newImage;
        } elseif (!isset($inputContent['hero']['image']) && isset($existingContent['hero']['image'])) {
            $inputContent['hero']['image'] = $existingContent['hero']['image'];
        }

        // Process Story Image Upload (About page)
        if ($request->hasFile('story_image')) {
            $oldImage = $existingContent['story']['image'] ?? null;
            $newImage = ImageHelper::uploadImage($request->file('story_image'), 'uploads/pages', $oldImage);
            $inputContent['story']['image'] = $newImage;
        } elseif (!isset($inputContent['story']['image']) && isset($existingContent['story']['image'])) {
            $inputContent['story']['image'] = $existingContent['story']['image'];
        }

        // Checkbox toggles based on slug
        if ($slug === 'home') {
            $inputContent['hero']['show_badge'] = $this->resolveToggle($request, 'content.hero.show_badge');
            $inputContent['features']['show_section'] = $this->resolveToggle($request, 'content.features.show_section');
            $inputContent['how_it_works']['show_section'] = $this->resolveToggle($request, 'content.how_it_works.show_section');
            $inputContent['why_invest']['show_section'] = $this->resolveToggle($request, 'content.why_invest.show_section');
            $inputContent['cta']['show_section'] = $this->resolveToggle($request, 'content.cta.show_section');

            if (isset($inputContent['hero']['trust_badges']) && is_array($inputContent['hero']['trust_badges'])) {
                $inputContent['hero']['trust_badges'] = array_values(array_filter($inputContent['hero']['trust_badges'], fn($item) => !empty($item['title']) || !empty($item['icon'])));
            }
            if (isset($inputContent['features']['items']) && is_array($inputContent['features']['items'])) {
                $inputContent['features']['items'] = array_values(array_filter($inputContent['features']['items'], fn($item) => !empty($item['title']) || !empty($item['desc'])));
            }
            if (isset($inputContent['how_it_works']['steps']) && is_array($inputContent['how_it_works']['steps'])) {
                $inputContent['how_it_works']['steps'] = array_values(array_filter($inputContent['how_it_works']['steps'], fn($item) => !empty($item['title']) || !empty($item['num'])));
            }
            if (isset($inputContent['why_invest']['cards']) && is_array($inputContent['why_invest']['cards'])) {
                $inputContent['why_invest']['cards'] = array_values(array_filter($inputContent['why_invest']['cards'], fn($item) => !empty($item['title']) || !empty($item['desc'])));
            }
        } elseif ($slug === 'about') {
            $inputContent['hero']['show_badge'] = $this->resolveToggle($request, 'content.hero.show_badge');
            $inputContent['stats']['show_section'] = $this->resolveToggle($request, 'content.stats.show_section');
            $inputContent['stats']['auto_calculate'] = $this->resolveToggle($request, 'content.stats.auto_calculate');
            $inputContent['story']['show_section'] = $this->resolveToggle($request, 'content.story.show_section');
            $inputContent['values']['show_section'] = $this->resolveToggle($request, 'content.values.show_section');
            $inputContent['process']['show_section'] = $this->resolveToggle($request, 'content.process.show_section');
            $inputContent['cta']['show_section'] = $this->resolveToggle($request, 'content.cta.show_section');

            if (isset($inputContent['story']['paragraphs']) && is_array($inputContent['story']['paragraphs'])) {
                $inputContent['story']['paragraphs'] = array_values(array_filter($inputContent['story']['paragraphs'], fn($p) => trim($p) !== ''));
            }
            if (isset($inputContent['story']['bullets']) && is_array($inputContent['story']['bullets'])) {
                $inputContent['story']['bullets'] = array_values(array_filter($inputContent['story']['bullets'], fn($b) => trim($b) !== ''));
            }
            if (isset($inputContent['values']['items']) && is_array($inputContent['values']['items'])) {
                $inputContent['values']['items'] = array_values(array_filter($inputContent['values']['items'], fn($v) => !empty($v['title'])));
            }
            if (isset($inputContent['process']['steps']) && is_array($inputContent['process']['steps'])) {
                $inputContent['process']['steps'] = array_values(array_filter($inputContent['process']['steps'], fn($s) => !empty($s['title'])));
            }
        } elseif ($slug === 'contact') {
            $inputContent['hero']['show_badge'] = $this->resolveToggle($request, 'content.hero.show_badge');
            $inputContent['cards']['show_section'] = $this->resolveToggle($request, 'content.cards.show_section');
            $inputContent['hours']['show_section'] = $this->resolveToggle($request, 'content.hours.show_section');
            $inputContent['whatsapp']['show_section'] = $this->resolveToggle($request, 'content.whatsapp.show_section');
            $inputContent['faq']['show_section'] = $this->resolveToggle($request, 'content.faq.show_section');

            if (isset($inputContent['form']['subjects']) && is_array($inputContent['form']['subjects'])) {
                $inputContent['form']['subjects'] = array_values(array_filter($inputContent['form']['subjects'], fn($s) => trim($s) !== ''));
            }
            if (isset($inputContent['hours']['items']) && is_array($inputContent['hours']['items'])) {
                $inputContent['hours']['items'] = array_values(array_filter($inputContent['hours']['items'], fn($h) => !empty($h['day'])));
            }
            if (isset($inputContent['faq']['items']) && is_array($inputContent['faq']['items'])) {
                $inputContent['faq']['items'] = array_values(array_filter($inputContent['faq']['items'], fn($f) => !empty($f['q'])));
            }
        } elseif ($slug === 'privacy' || $slug === 'terms') {
            $inputContent['hero']['show_badge'] = $this->resolveToggle($request, 'content.hero.show_badge');
            if (isset($inputContent['sections']) && is_array($inputContent['sections'])) {
                $inputContent['sections'] = array_values(array_filter($inputContent['sections'], fn($sec) => !empty($sec['title'])));
            }
        } elseif ($slug === 'opportunities') {
            $inputContent['hero']['show_badge'] = $this->resolveToggle($request, 'content.hero.show_badge');
            $inputContent['guest_banner']['show'] = $this->resolveToggle($request, 'content.guest_banner.show');
            $inputContent['bottom_features']['show_section'] = $this->resolveToggle($request, 'content.bottom_features.show_section');
            if (isset($inputContent['bottom_features']['items']) && is_array($inputContent['bottom_features']['items'])) {
                $inputContent['bottom_features']['items'] = array_values(array_filter($inputContent['bottom_features']['items'], fn($item) => !empty($item['label'])));
            }
        }

        // Merge updated content with existing structure
        $finalContent = array_replace_recursive($existingContent, $inputContent);

        // For list arrays, use explicit updated input so deleted items aren't restored by array_replace_recursive
        foreach (['sections', 'trust_badges', 'items', 'steps', 'cards', 'paragraphs', 'bullets', 'subjects'] as $arrKey) {
            if (isset($inputContent[$arrKey])) {
                $finalContent[$arrKey] = $inputContent[$arrKey];
            }
            foreach ($inputContent as $secKey => $secVal) {
                if (is_array($secVal) && isset($secVal[$arrKey])) {
                    $finalContent[$secKey][$arrKey] = $secVal[$arrKey];
                }
            }
        }

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

