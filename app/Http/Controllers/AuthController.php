<?php

namespace App\Http\Controllers;

use App\Modules\User\Models\User;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * @OA\Post(
     *      path="/api/sanctum/token",
     *      operationId="getAuthToken",
     *      @OA\RequestBody(
     *          required=true,
     *          @OA\JsonContent(
     *          @OA\Property(property="email", type="string"),
     *          @OA\Property(property="password", type="string"),
     *          @OA\Property(property="device_name", type="string"),
     *          )
     *      ),
     *      @OA\Response(
     *          response=201,
     *          description="Successful operation",
     *          @OA\JsonContent(
     *              @OA\Property(property="token", type="string"),
     *          )
     *       )
     * )
     */
    public function getToken(HttpRequest $request) //не стал переделывать под дто и сервис потому что это только для теста через сваггер
    {
      $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'device_name' => 'required',
      ]);
  
      $user = User::where('email', $request->email)->first();
  
      if (!$user || !Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
          'email' => ['The provided credentials are incorrect.'],
        ]);
      }
  
      $token = $user->createToken($request->device_name)->plainTextToken;
  
      return response()->json(['token' => $token], 200);
    }

}
