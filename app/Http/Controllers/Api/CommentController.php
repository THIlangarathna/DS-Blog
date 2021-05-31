<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Blog;
use App\Comment;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'comment' => ['required', 'string', 'max:255'],
        ]);

        $id = auth()->user()->id;

        $comment = new Comment;
        $comment -> user_id = $id;
        $comment -> blog_id = $request['blog_id'];
        $comment -> comment = $request['comment'];

        $comment->save();

        return response()->json(
            [
            'Status'=> 'Comment Added!',
            ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'comment' => ['required', 'string', 'max:255'],
        ]);

        $comment = Comment::find($id);
        $comment -> comment = $request['comment'];

        $comment->save();

        return response()->json(
            [
            'Status'=> 'Comment Updated!',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $comment = Comment::find($id);

        $comment->delete();

        return response()->json(
            [
            'Status'=> 'Comment Deleted!',
            ]);
    }
}
