<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        return response()->json([
            'user' => auth()->user(),
            'massage' => 'success'
        ]);
    }

    public function update(UserRequest $request)
    {
        $dataToUpdate = [
            'firstName' => $request->firstName,
            'lastName' => $request->lastName,
            'username' => $request->username,
            'email' => $request->email
        ];

        if (isset($request->password)) {
            $dataToUpdate['password'] = $request->password;
        }

        User::find(auth()->id())->update($dataToUpdate);

        return response()->json(['message' => 'Data successfully updated!']);
    }
}
