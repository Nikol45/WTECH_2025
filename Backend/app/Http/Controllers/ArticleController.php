<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    /* ====== LISTING ====== */
    public function index()
    {
        // články prihláseného admin‑užívateľa
        $articles = Article::where('user_id', Auth::id())->latest()->paginate(12);

        return view('articles.index', compact('articles'));
    }

    /* ====== FORM – CREATE ====== */
    public function create()
    {
        return view('articles.create');
    }

    /* ====== STORE ====== */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'text'  => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $data['user_id'] = Auth::id();

        // ak prišiel súbor -> uložíme
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')
                ->store('articles', 'public');
        }

        $article = Article::create($data);

        return back()->with('success', "Článok „{$article->title}“ bol pridaný.");
    }

    /* ====== SHOW ====== */
    public function show(Article $article)
    {
        return view('articles.show', compact('article'));
    }

    /* ====== FORM – EDIT ====== */
    public function edit(Article $article)
    {
        // môžeme skontrolovať, či práve prihlásený používateľ je autor,
        $this->authorize('update', $article);

        return view('articles.edit', compact('article'));
    }

    /* ====== UPDATE ====== */
    public function update(Request $request, Article $article)
    {
        $this->authorize('update', $article);

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'text'  => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        // nový súbor
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')
                ->store('articles', 'public');
        }

        $article->update($data);

        return back()->with('success', "Článok „{$article->title}“ bol upravený.");
    }

    /* ====== DESTROY ====== */
    public function destroy(Article $article)
    {
        if ($article->user_id !== auth()->id()) {
            abort(403);
        }

        $article->delete();

        return back()->with('success', 'Článok bol vymazaný.');
    }
}
