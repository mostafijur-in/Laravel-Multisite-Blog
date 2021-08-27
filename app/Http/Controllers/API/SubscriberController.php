<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Subscriber;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Auth;

use Exception;

class SubscriberController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function subscribe(Request $request)
    {
        $errors = [];

        $website_id = $request->website_id;
        $name       = $request->name;
        $email      = $request->email;

        if( empty($website_id) || !is_numeric($website_id) ) {
            $errors[]   = "website_id is required.";
        }

        if(!isValidName($name)) {
            $errors[]   = "Invalid name.";
        }

        if(!isValidEmail($email)) {
            $errors[]   = "Invalid email.";
        } else {
            if(Subscriber::where('email', $email)->count() > 0) {
                $errors[]   = "You have already subscribed to our mailing list.";
            }
        }

        if(empty($errors)) {
            try {
                Subscriber::create([
                    'website_id'    => $website_id,
                    'name'          => $name,
                    'email'         => $email,
                ]);

                return response()->json([
                    'status'    => 'success',
                    'message'   => 'You have subscribed successfull.',
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
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Subscriber  $subscriber
     * @return \Illuminate\Http\Response
     */
    public function destroy(Subscriber $subscriber)
    {
        //
    }
}
