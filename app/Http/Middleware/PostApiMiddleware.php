<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Laravel\Passport\Passport;

class PostApiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //verificar si el token es valido
        $tokenIsValid = Auth::guard('api')->check();

        $user = Auth::guard('api')->user();

        // return response()->json([
        //     'scopes' => Passport::scopes(),
        //     'tokenIsValid' => $tokenIsValid,
        //     'has scope' => Passport::hasScope('read-post'),
        //     'user' => Auth::guard('api')->user(),
        //     'user scope read' => Auth::guard('api')->user()->tokenCan('read-post'),
        //     'user scope create' => Auth::guard('api')->user()->tokenCan('create-post'),
        //     'user has role' => $user->roles,
        //     'user has permission' => $user->roles->first()->permissions,
        // ]);


        if(!$tokenIsValid){

            if($request->route()->named('api.v1.posts.index')){
                return $next($request);
            }
    
            if($request->route()->named('api.v1.posts.show')){
                return $next($request);
            }

            return response()->json(['message' => 'Unauthorized'], 401);
           
        }else{

           if($request->route()->named('api.v1.posts.update')){
               if($user->tokenCan('update-post') && $user->id == $request->post->user_id){
                    return $next($request);
               }else{
                    return response()->json(['message' => 'Unauthorized'], 401);
               }
           }

        }

    }
}
