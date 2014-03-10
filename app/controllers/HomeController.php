<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function Home()
	{

		$data = array(
			"js" => array("js/jquery-1.11.0.min.js", "bootstrap/js/bootstrap.min.js", "js/xhr2.js", "js/upload.js", "js/photo.js")
		);
		
		$image = new Image;

		$data['images'] = $image->getLastImages(8);

		return View::make('view')->with($data);
	}

	public function Register()
	{

		$data = array();

		if (Request::isMethod('post')) {

			$rules = array(
				'first_name' => 'required|alpha|digits_between:2,64',
				'last_name' => 'required|alpha|digits_between:2,64',
				'email' => 'required|email|unique:user',
				'password' => 'required|digits_between:8,64',
				'confirm_password' => 'required|same:password'
			);

			$label = array(
				'first_name' => Lang::get('form.first_name'),
				'last_name' => Lang::get('form.last_name'),
				'email' => Lang::get('form.email'),
				'password' => Lang::get('form.password'),
				'confirm_password' => Lang::get('form.confirm_password')
			);

			$validator = Validator::make(Input::all(), $rules, array(), $label);
			$data['messages'] = $validator->messages();
			$data['failed'] = $validator->failed();

			if ($validator->fails()) {

				Input::flash();
				return View::make('register')->with($data);

			} else {

				$user = new User;

				$user->first_name = Input::get('first_name');
				$user->last_name = Input::get('last_name');
				$user->email = Input::get('email');
				$user->password = Hash::make(Input::get('password'));

				if ($user->save() && Auth::loginUsingId($user->id)) {
					return Redirect::action('HomeController@Home', array(), 303);
				}

			}

		}

		return View::make('register_before')->with($data);
	}

	public function login()
	{
		$data = array();

		if (Request::isMethod('post')) {

			$input = array(
				"email" => Input::get('email'),
				"password" => Input::get('password')
			);

			if(Auth::attempt($input)) {
				return Redirect::action('HomeController@Home', array(), 303);
			} else {
				$data['error'] = 1;
				Input::flash();
			}

		} 

		return View::make('login')->with($data);
	}

	public function Upload()
	{
		$image_file = Input::file("inputFile");
		$image = new Image;

		if($image->upload($image_file)) {
			$web_width = $image->_web_width;
			$web_height = $image->_web_height;
			$thumbnail_name = $image->_thumbnail_name;
			$web_name = $image->_web_name;
			$original_name = $image->_original_name;
			$error = $image->error;
			
			$image = new Image;
			$image->title = Input::get('title');
			$image->user_id = Auth::user()->id;
			$image->active = 1;
			$image->web_string = 'width="'.$web_width.'" height="'.$web_height.'"';
			$image->web_width = $web_width;
			$image->web_height = $web_height;
			$image->original_name = $original_name;
			$image->thumbnail_name = $thumbnail_name;
			$image->web_name = $web_name;

			if($image->save()) {
				echo json_encode(array('title'=> Input::get('title'), 'thumbnail_name'=> $thumbnail_name, 'id'=> $image->id));
			}
		} else {
			echo json_encode(array('error' => $error));
		}

	}
}