<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\NewsOneResource;
use App\Http\Resources\NewsResource;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NewsController extends Controller
{


    public function getNews()
    {
        $news = News::get();

        return NewsResource::collection($news);
    }

    public function getOneNews($id)
    {
        $news = News::find($id)->first();

        return new NewsOneResource($news);
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

        $news = News::create([
            'header' => $validated['titre_news'],
            'subheader' => $validated['sous_titre_news'],
            'description' => $validated['description_news'],
        ]);

        if ($request->hasFile('image_news')) {
            $imagePath = $request->file('image_news')->store('news_images', 'public');
            $news->image = base64_encode($imagePath);
        }
        return response()->json(['message' => 'News created successfully!'], 200);
    }

    public function updateNews(Request $request, $id)
    {
        $data = $request->all();
        Log::info($data);

        $validated = $request->validate([
            'titre_news' => 'required|string',
            'sous_titre_news' => 'required|string',
            'description_news' => 'required|string',
            'image_news' => 'nullable|image|max:1024',
        ]);

        $news = News::findOrFail($id);

        if ($request->hasFile('image_news')) {
            $imagePath = $request->file('image_news')->store('news_images', 'public');
            $news->image = base64_encode($imagePath);
        }
        $data = [
            'header'        => $validated['titre_news'] ?? null ,
            'subheader'     => $validated['sous_titre_news'] ?? null,
            'description'   => $validated['description_news'] ?? null,
        ];

        $news->update($data);

        return response()->json(['message' => 'News updated successfully !'], 200);
    }

}
