## Build API basic with JWTAuth v1.0


#BEGIN

Step 1:

	Create database
	
Step 2: Run composer to build db

	php artisan migrate	


#SOURCECODE

## composer.json

    "tymon/jwt-auth": "^0.5.9",


## config/app.php


    //JWTAUTH IM HERE
    Tymon\JWTAuth\Providers\JWTAuthServiceProvider::class,

    //JWTAUTH IM HERE
    'JWTAuth' => Tymon\JWTAuth\Facades\JWTAuth::class,

## Except Token in API

Laravel 5.1:

	protected $except = [
	    'api/*'
	];

IF Laravel 5.2:

    public function handle($request, Closure $next)
	{
    	if ( ! $request->is('api/*'))
    	{
    	   return parent::handle($request, $next);
    	}
    	return $next($request);
	}


## app\Http\Middleware\authJWT.php:


	use Closure;
	use JWTAuth;
	use Exception;

    public function handle($request, Closure $next)
    {
        try {
            
            $user = JWTAuth::toUser($request->input('token'));
        
        } catch (Exception $e) {
                
            if($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){

                return response()->json(['error' => 'Token is Invalid']);

            }else if($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){

                return response()->json(['error' => 'Token is Expired']);

            }else{

                return response()->json(['error' => 'Something is wrong, ask to Simon!']);

            }
        }

        return $next($request);
    }

## app\Http\Middleware\CORS.php

    public function handle($request, Closure $next)
    {

        header('Access-Control-Allow-Origin: *');
        
        $headers = [
            'Access-Control-Allow-Methods'=> 'POST, GET, OPTIONS, PUT, DELETE',
            'Access-Control-Allow-Headers'=> 'Content-Type, X-Auth-Token, Origin'
        ];
        if($request->getMethod() == "OPTIONS") {
            return Response::make('OK', 200, $headers);
        }
        
        $response = $next($request);
        foreach($headers as $key => $value)
            $response->header($key, $value);
        return $response;

    }




## app\Http\Kernel.php:

    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        //JWTAUTH IM HERE
        'jwt-auth' => \App\Http\Middleware\authJWT::class,
        'cors' => \App\Http\Middleware\CORS::class,

    ];


## app\Http\routes.php:


	Route::group(['middleware' => ['cors'], 'prefix' => 'api'], function(){

		Route::post('register', 'APIController@register');

		Route::post('login', 'APIcontroller@login');

		//JWTAUTH IM HERE
		Route::group(['middleware' => 'jwt-auth'], function(){

			Route::post('get_user_details', 'APIController@get_user_details');

		});
	});


## APIController


    public function register(Request $request){

        $input = $request->all();
        $input['password'] = Hash::make($input['password']);
        User::create($input);
        return response()->json(['result' => true]);
    }

    public function login(Request $request){

        $input = $request->all();

        if(!$token = JWTAuth::attempt($input)){

            return response()->json(['result' => 'worng email or password']);
        
        }

        return response()->json(['result' => $token]);
    }


    public function get_user_details(Request $request){

        $input = $request->all();

        $user = JWTAuth::toUser($input['token']);

        return response()->json(['result' => $user]);

    }


#IMPLEMENT

## URL API POST

Step 1:

	http://localhost:8000/api/register

Step 2: Login account -> fetch Token

	http://localhost:8000/api/login

Step 3: 	

	http://localhost:8000/api/get_user_details
