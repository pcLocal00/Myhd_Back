<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\CatalogueResource;
use App\Http\Resources\FamilleResource;
use App\Http\Resources\NewsResource;
use App\Models\Catalogue;
use App\Models\Famille;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CatalogueController extends Controller
{
    public function getCatalogue()
    {
        $catalogues = Catalogue::get();

        return CatalogueResource::collection($catalogues);
    }

    public function getFamille()
    {
        $familles = Famille::get();

        return FamilleResource::collection($familles);
    }

    public function getNews()
    {
        $news = News::get();

        return NewsResource::collection($news);
    }

    public function getOneNews($id)
    {
        $news = News::find($id)->first();

        return $news;
    }

    public function addNews(Request $request)
    {
        $data = $request->all();
        Log::info($data);

        $validated = $request->validate([
            'titre_news' => 'required|string',
            'sous_titre_news' => 'required|string',
            'description_news' => 'required|string',
            'image_news' => 'nullable|image|max:1024',
        ]);

        if ($request->hasFile('image_news')) {
            $imagePath = $request->file('image_news')->store('news_images', 'public');
            $validated['image'] = $imagePath;
        }

        News::create([
            'header' => $validated['titre_news'],
            'subheader' => $validated['sous_titre_news'],
            'description' => $validated['description_news'],
            'image' => $validated['image'] ?? null, // Use image path if uploaded
        ]);

        return response()->json(['message' => 'News created successfully!'], 200);
    }

}
