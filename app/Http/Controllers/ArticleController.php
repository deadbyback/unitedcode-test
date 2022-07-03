<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Article;

class ArticleController extends Controller
{
    public function index()
    {
        // TODO: friend the back and front paginations
        //$articles = Article::paginate(10);
        $articles = Article::all();
        return response()->json($articles, 200);
    }

    public function getWithAuthors()
    {
        $articles = Article::select('articles.*', 'authors.name')
            ->leftJoin('authors',  'articles.author_id', '=', 'authors.id')
            ->orderBy('authors.name', 'asc')
            ->get();

        return response()->json($articles, 200);
    }

    // TODO: make some CRUD logic

    public function show($id)
    {
        return Article::find($id);
    }

    public function store(Request $request)
    {
        $article = Article::create($request->all());
        return response()->json($article, 201);
    }

    public function update(Request $request, $id)
    {
        $article = Article::findOrFail($id);
        $article->update($request->all());

        return response()->json($article, 200);
    }

    public function delete($id)
    {
        $article = Article::findOrFail($id);
        $article->delete();

        return response()->json(null, 204);
    }
}
