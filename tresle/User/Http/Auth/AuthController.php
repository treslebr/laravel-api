<?php


namespace Tresle\User\Http\Auth;


use Carbon\Carbon;
use ErrorException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use RuntimeException;
use Tresle\User\Http\Requests\UserRequest;
use Tresle\User\Model\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends \App\Http\Controllers\Controller
{

    /**
     * @var bool
     */
    protected $isAdmin = true;

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string',
                'email' => 'required|string|email|unique:users',
                'password' => 'required|string|confirmed',
                'telephone' => 'required|string',
            ]);
            $user = new User([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'telephone' => $request->telephone,
                'cellphone' => $request->cellphone,
                'is_admin' => $this->isAdmin,
            ]);
            $user->save();
            return response()->json([
                'message' => 'Successfully created user!'
            ], 201);
        } catch (ModelNotFoundException $e) {
            return response(["errors" => true, "message" => "Não foi possível cadastrar usuário."], 404);
        } catch (ErrorException $e) {
            return response(["errors" => true, "message" => "Erro no servidor."], 500);
        }
    }

    /**
     * @param UserRequest $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function login(UserRequest $request)
    {
        try {
            $credentials = request(['email', 'password']);

            if (!Auth::attempt($credentials)) {
                return response()->json([
                    'message' => 'Unauthorized'
                ], 401);
            }
            $user = $request->user();
            $tokenResult = $user->createToken('Personal Access Token');
            $token = $tokenResult->token;
            if ($request->remember_me)
                $token->expires_at = Carbon::now()->addWeeks(1);
            $token->save();
            return response()->json([
                'access_token' => $tokenResult->accessToken,
                'token_type' => 'Bearer',
                'expires_at' => Carbon::parse(
                    $tokenResult->token->expires_at
                )->toDateTimeString(),
                'is_admin' => $user->is_admin
            ]);
        } catch (ModelNotFoundException $e) {
            return response(["errors" => true, "message" => "Usuário não encontrado."], 404);;
        } catch (RuntimeException $e) {
            return response(["errors" => true, "message" => "Forbidden"], 403);
        } catch (ErrorException $e) {
            return response(["errors" => true, "message" => "Erro no servidor."], 500);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function getUserLogged(Request $request)
    {
        try {
            return response()->json(Auth::user());
        } catch (ErrorException $e) {
            return response(["errors" => true, "message" => "Erro no servidor."], 500);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\JsonResponse|\Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        try {
            $request->user()->token()->revoke();
            return response()->json([
                'message' => 'Successfully logged out'
            ]);
        } catch (ErrorException $e) {
            return response(["errors" => true, "message" => "Erro no servidor."], 500);
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return array|\Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     */
    public function destroy(\Illuminate\Http\Request $request, $id)
    {
        try {
            $user = User::findOrFail((int)$id);
            $user->delete();
            return ["error" => false, "message" => ""];
        } catch (ModelNotFoundException $e) {
            return response(["errors" => true, "message" => "Usuário não encontrado."], 404);
        } catch (\Illuminate\Database\QueryException $e) {
            $mensagem = "Não foi possível excluir o usuário.";
            return response(["errors" => true, "message" => $mensagem], 422);
        } catch (ErrorException $e) {
            return response(["errors" => true, "message" => "Erro no servidor."], 500);
        }
    }
}

