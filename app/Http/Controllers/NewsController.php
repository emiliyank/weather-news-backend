<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\User;
use App\News;

class NewsController extends Controller
{
     /**
     * Instantiate a new UserController instance.
     *
     * @return void
     */
    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function index()
    {
        return response()->json(['all_news' => News::with(['author'])->orderBy('created_at', 'desc')->get()], 200);
    }

    public function newsListWithCount(int $count)
    {
        $news = News::with(['author'])->orderBy('created_at', 'desc')->limit($count)->get();
        return response()->json(['news' => $news], 200);
    }

    public function store(Request $request)
    {
        //validate incoming request 
        $this->validate($request, [
            'title' => 'required|string',
            'content' => 'required|string',
            'author_id' => 'sometimes'
        ]);

        try {
            $news = new News;
            $news->title = $request->input('title');
            $news->content = $request->input('content');
            
            if(Auth::check()){
                $news->author_id = Auth::id();
            } elseif ($request->input('author_id')) {
                $news->author_id = $request->input('author_id');
            }

            $news->save();

            //return successful response
            return response()->json(['news' => $news, 'message' => 'CREATED'], 201);

        } catch (\Exception $e) {
            //return error message
            return response()->json(['message' => 'News Creation Failed! ' . $e], 409);
        }
    }

    /**
     * Get one news.
     *
     * @return Response
     */
    public function show($id)
    {
        try {
            $news = News::findOrFail($id);

            return response()->json(['news' => $news], 200);

        } catch (\Exception $e) {
            return response()->json(['message' => 'news not found!' . $е], 404);
        }
    }

    public function update($id, Request $request)
    {
        //validate incoming request 
        $this->validate($request, [
            'title' => 'required|string',
            'content' => 'required|string'
        ]);

        try {
            $news = News::findOrFail($id);
            $news->title = $request->input('title');
            $news->content = $request->input('content');
            $news->save();

            return response()->json(['news' => $news, 'message' => 'UPDATED'], 201);

        } catch (\Exception $e) {
            return response()->json(['message' => 'News Update Failed!' . $е], 409);
        }
    }

    public function delete($id)
    {
        try {
            $news = News::findOrFail($id);
            $news->delete();

            return response()->json(['message' => 'DELETED'], 201);

        } catch (\Exception $e) {
            return response()->json(['message' => 'News Delete Failed!' . $е], 409);
        }
    }
}