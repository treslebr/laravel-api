<?php


namespace Tresle\User\Http\Auth;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use RuntimeException;
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
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request){
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
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {

        try {
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
                'remember_me' => 'boolean'
            ]);
            $credentials = request(['email', 'password']);
            if(!Auth::attempt($credentials))
                return response()->json([
                    'message' => 'Unauthorized'
                ], 401);
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
        }
        catch (ModelNotFoundException $e) {
            return ["error" => true, "message" => "Erro ao logar"];
        }catch (RuntimeException $e) {
                return ["error" => true, "message" => $e->getMessage()];
            }



    }

    /**
     * Get the authenticated User
     *
     * @return [json] user object
     */
    public function getUserLogged(Request $request)
    {
        return response()->json(Auth::user());
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout(Request $request)
    {
        $request->user()->token()->revoke();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    /**
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return array
     * @throws \Exception
     */
    public function destroy(\Illuminate\Http\Request $request, $id)
    {
        try {
            $user = User::findOrFail((int)$id);
            $user->delete();
            return ["error" => false, "message" => ""];
        } catch (ModelNotFoundException $e) {
            return ["error" => true, "message" => self::NAO_ENCONTRADO];
        }catch (\Illuminate\Database\QueryException $e) {
            $mensagem = "Erro ao excluir o usuário";
            return ["error" => true, "message" => $mensagem];
        }
    }
}

