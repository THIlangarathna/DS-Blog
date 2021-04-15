<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Blog;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id = auth()->user()->id;
        $user = User::find($user_id);
        $blogs = Blog::where('user_id', '=', $user_id)->get();

        return response()->json(
            [
            'user' => $user,
            'blogs' => $blogs,
            ]);
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
    public function storeimg(Request $request)
    {
        $imagePath = request('img')->store('uploads','public');

        return response()->json(
        [
        'url'=> $imagePath,
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'category' => ['required', 'string', 'max:255'],
        ]);

        $id = auth()->user()->id;

        $blog = new Blog;
        $blog -> user_id = $id;
        $blog -> title = $request['title'];
        $blog -> description = $request['description'];
        $blog -> category = $request['category'];

        $blog->save();

        return response()->json(
            [
            'Status'=> 'Success!',
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
        $user_id = auth()->user()->id;
        $user = User::find($user_id);
        $blog = Blog::find($id);
        $my_comments = Comments::where('user_id', '=', $user_id)->where('blog_id','=',$id)->get();
        // $comments = Comments::where('user_id', '!=', $user_id)->orWhereNull('user_id')->get();
        $others_comments = Comments::where('user_id', '!=', $user_id)->where('blog_id','=',$id)->get();

        return response()->json(
            [
            'user' => $user,
            'blog' => $blog,
            'my_comments' => $my_comments,
            'others_comments' => $others_comments,
            ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user_id = auth()->user()->id;
        $user = User::find($user_id);
        $blog = Blog::find($id);

        return response()->json(
            [
            'user' => $user,
            'blog' => $blog,
            ]);
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
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
            'category' => ['required', 'string', 'max:255'],
        ]);
        $blog = Blog::find($id);
        $blog -> title = $request['title'];
        $blog -> description = $request['description'];
        $blog -> category = $request['category'];
        $blog->save();


        return response()->json(
        [
        'Status'=> 'Blog Updated.',
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
        $blog = Blog::find($id);
        $blog ->delete();
        
        return response()->json(
        [
        'Status'=> 'Blog Deleted!',
        ]);
    }
}
