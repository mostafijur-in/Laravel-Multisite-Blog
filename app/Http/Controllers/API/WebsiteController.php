<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Website;
use App\Models\UserWebsite;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Exception;

class WebsiteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_id    = Auth::id();
        $websites   = Website::where('user_websites.user_id', $user_id)
            ->join('user_websites', 'websites.id', '=', 'user_websites.website_id')
            ->get();

        return response()->json([
            'status'    => 'success',
            'data'      => $websites
        ], 200);
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

        $title          = $request->title;
        $description    = $request->description;
        // $status         = $request->status;

        if( empty($title) || !preg_match('/^[a-zA-Z0-9 \-]+$/i', $title) ) {
            $errors[]   = "Invalid title.";
        }
        if( empty($description) ) {
            $errors[]   = "Description is required.";
        }

        if(empty($errors)) {
            try {
                $Website   = Website::create([
                    'title'      => $title,
                    'description'     => $description,
                ]);

                UserWebsite::create([
                    'user_id'       => $user_id,
                    'website_id'    => $Website->id,
                ]);

                return response()->json([
                    'status'    => 'success',
                    'message'   => 'Website created successfull.',
                    'data'      => $Website,
                ], 200);
            } catch (Exception $e) {

                return response()->json([
                    'status'    => 'error',
                    'message'   => 'Unable to create website. '. $e->getMessage()
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
     * @param  \App\Models\Website  $website
     * @return \Illuminate\Http\Response
     */
    public function show(Website $website)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Website  $website
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Website $website)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Website  $website
     * @return \Illuminate\Http\Response
     */
    public function destroy(Website $website)
    {
        //
    }
}
