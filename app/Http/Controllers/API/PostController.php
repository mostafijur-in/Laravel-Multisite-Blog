<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Exception;
use Illuminate\Support\Facades\Artisan;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $errors = [];

        // $user_id    = Auth::id();
        $user    = Auth::user();
        $website_id = $request->website_id;

        if( empty($website_id) || !is_numeric($website_id) ) {
            $errors[]   = "website_id is required.";
        }

        if(empty($errors)) {
            $posts   = $user->posts()->where('website_id', $website_id)->get();

        return response()->json([
            'status'    => 'success',
            'data'      => $posts
        ], 200);
        } else {
            return response()->json([
                'status'    => 'error',
                'message'   => 'ERROR: '. implode('\n', $errors)
            ], 401);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $errors = [];

        $user_id    = Auth::id();

        $website_id     = $request->website_id;
        $title          = $request->title;
        $description    = $request->description;
        $status         = $request->status;

        if( empty($website_id) || !is_numeric($website_id) ) {
            $errors[]   = "website_id is required.";
        }

        if( empty($title) || !preg_match('/^[a-zA-Z0-9 \-]+$/i', $title) ) {
            $errors[]   = "Invalid title.";
        }
        if( empty($description) ) {
            $errors[]   = "Description is required.";
        }

        if( empty($status) || !in_array($status, ['draft', 'publish']) ) {
            $errors[]   = "Invalid post status.";
        }

        if(empty($errors)) {
            $slug   = slugify($title);
            try {
                $Post   = Post::create([
                    'user_id'    => $user_id,
                    'website_id'    => $website_id,
                    'title'         => $title,
                    'slug'          => $slug,
                    'description'   => $description,
                    'status'        => $status,
                ]);

                if($status === 'publish') {
                    Artisan::call("mail:sendpostnotification {$Post->id} --queue");
                }

                return response()->json([
                    'status'    => 'success',
                    'message'   => 'Post created successfull.',
                    'data'      => $Post,
                ], 200);
            } catch (Exception $e) {

                return response()->json([
                    'status'    => 'error',
                    'message'   => 'Unable to create post. '. $e->getMessage()
                ], 401);
            }
        } else {
            return response()->json([
                'status'    => 'error',
                'message'   => 'ERROR: '. implode('\n', $errors)
            ], 401);
        }
    }


    /**
     * Update post status.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update_status(Request $request)
    {
        $errors = [];

        $post_id     = $request->post_id;
        $status         = $request->status;

        if( empty($post_id) || !is_numeric($post_id) ) {
            $errors[]   = "post_id is required.";
        }

        if( empty($status) || !in_array($status, ['draft', 'publish']) ) {
            $errors[]   = "Invalid post status.";
        }

        if(empty($errors)) {
            try {
                Post::find($post_id)->update([
                    'status'        => $status,
                ]);

                if($status === 'publish') {
                    Artisan::call("mail:sendpostnotification {$post_id} --queue");
                }

                return response()->json([
                    'status'    => 'success',
                    'message'   => 'Post status update successfull.',
                ], 200);
            } catch (Exception $e) {

                return response()->json([
                    'status'    => 'error',
                    'message'   => 'Unable to create post. '. $e->getMessage()
                ], 401);
            }
        } else {
            return response()->json([
                'status'    => 'error',
                'message'   => 'ERROR: '. implode('\n', $errors)
            ], 401);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        //
    }

}
