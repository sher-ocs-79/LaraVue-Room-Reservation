<?php
declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Contracts\UserRepositoryInterfaceContract;
use Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    /**
     * @var UserRepositoryInterfaceContract
     */
    protected $userRepository;

    public function __construct(UserRepositoryInterfaceContract $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
	 * Register
	 */
	public function register(Request $request)
	{
		try {
            $request->validate([
                'name' => 'required|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|alpha_num',
            ]);

            $this->userRepository->create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
			$success = true;
			$message = 'User register successfully';
		} catch (\Illuminate\Database\QueryException $exception) {
			$success = false;
			$message = $exception->getMessage();
		}

		$response = [
			'success' => $success,
			'message' => $message,
		];
		return response()->json($response);
	}

	public function login(Request $request)
	{
        $user = $this->userRepository->getByEmail($request->email);

        if (!empty($user) === true && Hash::check($request->password, $user->getAuthPassword()) === true) {
            Auth::login($user);
            $token = $user->createToken('auth_token')->plainTextToken;
            $response = [
                'success' => TRUE,
                'access_token' => $token,
                'token_type' => 'Bearer',
            ];
		} else {
			$response = [
				'success' => FALSE,
				'message' => 'Unauthorised',
			];
		}
		return response()->json($response);
	}

	public function logout()
	{
		try {
			Session::flush();
			$success = true;
			$message = 'Successfully logged out';
		} catch (\Illuminate\Database\QueryException $exception) {
			$success = false;
			$message = $exception->getMessage();
		}
		$response = [
			'success' => $success,
			'message' => $message,
		];
		return response()->json($response);
	}
}
