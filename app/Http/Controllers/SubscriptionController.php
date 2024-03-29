<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Exception;


class SubscriptionController extends Controller
{

    public function __construct()
    {
        $this->middleware(['auth:sanctum']);
        $this->middleware(['subscribed'])->only('update');
    }


    public function store(Request $request)
    {
        // $this->validate($request, [
        //     'plan' => ['nullable', 'exists:plans,slug'],
        //     'token' => ['required']
        // ]);

        $validation = Validator::make($request->all() ,[
           'plan' => ['nullable', 'exists:plans,slug'],
            'token' => ['required']
        ]);


        if($validation->fails()) {
            return response()->json([
                'errors' => $validation->errors()
            ]);
        }


        $plan = Plan::whereSlug($request->get('plan', 'medium'))->first();

        $request->user()->newSubscription('default', $plan->stripe_id)->create($request->token);
    }


    public function update(Request $request)
    {

        $validation = Validator::make($request->all() ,[
           'plan' => ['required', 'exists:plans,slug'],
        ]);


        if($validation->fails()) {
            return response()->json([
                'errors' => $validation->errors()
            ]);
        }

        $plan = Plan::whereSlug($request->plan)->first();

        if(!$request->user()->canDowngradeToPlan($plan)){

            return response()->json([
                'errors' => ['downgrade_error' => 'You cannot downgrade to the '.$plan->name.' plan, as you are using more storage than given plan provides.']
            ]);
        }
        

        if (!$plan->buyable) {
            $request->user()->subscription('default')->cancel();
            return;
        }

        $request->user()->subscription('default')->swap($plan->stripe_id);
    }
}
