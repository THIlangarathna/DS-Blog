<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\User;
use App\Blog;
use App\Comment;
use DB;

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
    public function all()
    {
        $blogs = DB::table('blogs')
        ->join('users', 'blogs.user_id', '=', 'users.id')
        ->select('blogs.id','blogs.title','blogs.img','blogs.intro','blogs.category','blogs.created_at','users.name')
        ->orderBy('blogs.id', 'DESC')
        ->get();

        return response()->json(
            [
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
        $imgurl = request('upload')->store('uploads','s3');
        $function_number = $request['CKEditorFuncNum'];
        $message = '';
        $url = "https://ds-bucket-final.s3.ap-south-1.amazonaws.com/$imgurl";
        return "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($function_number, '$url', '$message');</script>";
    }

    public function img(Request $request)
    {
        $imagePath = request('img')->store('uploads','s3');

        return response()->json(
        [
        'url'=> $imagePath,
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'intro' => ['required', 'string', 'max:255'],
            'img' => ['required'],
            'content' => ['required', 'string'],
            'category' => ['required', 'string', 'max:255'],
        ]);

        $id = auth()->user()->id;

        $blog = new Blog;
        $blog -> user_id = $id;
        $blog -> title = $request['title'];
        $blog -> intro = $request['intro'];
        $imagePath = request('img')->store('uploads','s3');
        $blog -> img = $imagePath;
        $blog -> content = $request['content'];
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
        //$my_comments = Comment::where('user_id', '=', $user_id)->where('blog_id','=',$id)->get();
        // $comments = Comment::where('user_id', '!=', $user_id)->orWhereNull('user_id')->get();
        //$others_comments = Comment::where('user_id', '!=', $user_id)->where('blog_id','=',$id)->get();

	$my_comments = DB::table('comments')
        ->join('users', 'comments.user_id', '=', 'users.id')
        ->where('comments.blog_id',$id)
        ->where('comments.user_id',$user_id)
        ->select('comments.id','comments.comment','comments.created_at','users.name','users.img')
        ->orderBy('id', 'DESC')
        ->get();

        $others_comments = DB::table('comments')
        ->join('users', 'comments.user_id', '=', 'users.id')
        ->where('comments.blog_id',$id)
        ->where('comments.user_id','!=',$user_id)
        ->select('comments.id','comments.comment','comments.created_at','users.name','users.img')
        ->orderBy('id', 'DESC')
        ->get();

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
            'intro' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'category' => ['required', 'string', 'max:255'],
        ]);
        $blog = Blog::find($id);
        $blog -> title = $request['title'];
        $blog -> intro = $request['intro'];
        $blog -> content = $request['content'];
        $blog -> category = $request['category'];
        if ($request->hasFile('img')) {
            $imagePath = request('img')->store('uploads','s3');
            $blog -> img = $imagePath;
            $blog->save();
        }
        else{
            $blog->save();
        }

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
    public function home()
    {
        $blogs = DB::table('blogs')
        ->join('users', 'blogs.user_id', '=', 'users.id')
        ->select('blogs.id','blogs.title','blogs.img','blogs.intro','blogs.category','blogs.created_at','users.name')
        ->orderBy('blogs.id', 'DESC')
        ->limit(4)
        ->get();

        return response()->json(
            [
            'blogs' => $blogs,
            ]);
    }
    public function showblog($id)
    {
        $blog = Blog::find($id);
        $user_id = $blog->user_id;
        $user = User::find($user_id);
        $comments = DB::table('comments')
	->join('users', 'comments.user_id', '=', 'users.id')
        ->where('comments.blog_id','=',$id)
        ->select('comments.id','comments.comment','comments.created_at','users.name','users.img')
        ->orderBy('id', 'DESC')
        ->get();

        return response()->json(
            [
            'user' => $user,
            'blog' => $blog,
            'comments' => $comments,
            ]);
    }
}

