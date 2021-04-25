<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{

    /**
     * Create a new User
     *
     * @param Request $request
     * @return void
     */
    public function create(Request $request)
    {
        try {
            $this->validate($request, [
                'email' => 'required|email|unique:users',
                'password' => 'required|min:6',
                'name' => 'required',
                'phone' => 'required'
            ]);

            if ($request->has('token')) {
                $user->fill($request->except('password', 'confirm') + [
                    'password' => password_hash($request->password, PASSWORD_DEFAULT)
                ]);
            } else {
                $user = User::create($request->except('password', 'confirm') + [
                    'password' => password_hash($request->password, PASSWORD_DEFAULT)
                ]);
            }

            $apikey = 'new-user';
            $user->api_key = $apikey;
            $user->save();

            return response()->json(['status' => 'success', 'user' => $user, 'token' => $apikey]);
        } catch (\Exception $e) {
            $errors = \array_values($e->errors());
            return response()->json(['status' => 'fail', 'error' => $errors[0][0] ], 400);
        }
    }

    /**
     * Verify if User exist
     *
     * @param Request $request
     * @return boolean
     */
    public function hasUser(Request $request)
    {
        $user = User::where('email',$request->email)->first();

        if ($user !== null) {
            return response()->json(['status' => true]);
        }

        return response()->json(['status' => false]);
    }

    public function findUsers (Request $request) {
        $users = User::where('name','like','%'.$request->search.'%')
                ->orWhere('email', 'like', '%' . $request->search . '%')->get();

        return response()->json($users);
    }

    /**
     * Set a new friend getting the "id" and set in array
     *
     * @param Request $request
     * @param [type] $id
     * @return void
     */
    public function addFriend (Request $request, $id) {
        $friend = User::findOrFail($request->friend_id);

        if ($friend) {
            $user = User::where('_id', $id)->push(['friend' => $friend->_id]);

            return response()->json(['message' => $friend->name.' foi adicionado!']);
        }
        return response()->json(['message' => 'Usuário não encontrado.']);
    }

    /**
     * Search a friend by "id"
     *
     * @param [type] $id
     * @return void
     */
    public function searchFriends ($id) {
        $user = User::findOrFail($id);

        if ($user) {
            $friends = User::findMany($user->friend);

            return response()->json($friends);
        }
    }

    /**
     * Search user by "id"
     *
     * @param [type] $id
     * @return void
     */
    public function searchUser ($id) {
        $user = User::findOrFail($id);

        return response()->json($user); 
    }
}
