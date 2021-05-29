<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Response\BaseController as BaseController;
use App\Models\Article;
use App\Http\Requests\ArticleRequests;
use App\Http\Resources\Article as ArticleResource;

class ArticleController extends BaseController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    function __construct()
    {
         $this->middleware('permission:article-list|article-create|article-edit|', ['only' => ['index','store']]);
         $this->middleware('permission:article-create', ['only' => ['create','store']]);
         $this->middleware('permission:article-edit', ['only' => ['edit','update','destroy']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $articles = Article::all();

        return $this->sendResponse(ArticleResource::collection($articles), 'Article retrieved successfully.');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(ArticleRequests $request)
    {
        $input = $request->all();

        $article = Article::create($input);
   
        return $this->sendResponse(new ArticleResource($article), 'Article created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $article = Article::find($id);
  
        if (is_null($article)) {
            return $this->sendError('Article not found.');
        }
   
        return $this->sendResponse(new ArticleResource($article), 'Article retrieved successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(ArticleRequests $request, Article $article)
    {
        $input = $request->all();

        $article->title     = $input['title'];
        $article->author    = $input['author'];
        $article->detail    = $input['detail'];
        $article->save();
   
        return $this->sendResponse(new ArticleResource($article), 'Article created successfully.');
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Article $article)
    {
        $article->delete();

        return $this->sendResponse([], 'Article deleted successfully.');
    }
}
