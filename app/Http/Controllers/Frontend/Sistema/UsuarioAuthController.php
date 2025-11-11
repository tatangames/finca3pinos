<?php

namespace App\Http\Controllers\Frontend\Sistema;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetMail;
use App\Models\Departamento;
use App\Models\Direcciones;
use App\Models\DireccionFacturacion;
use App\Models\Municipio;
use App\Models\Ordenes;
use App\Models\Pais;
use App\Models\Producto;
use App\Models\ProductosPresentacion;
use App\Models\Usuario;
use App\Traits\HandlesCart;
use Carbon\Carbon;
use Darryldecode\Cart\Facades\CartFacade as Cart;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;


class UsuarioAuthController extends Controller
{

    use HandlesCart;

    public function __construct()
    {
        $this->middleware('auth:web')->except(['showLoginFormUsuario', 'loginUsuario', 'showIngresarCorreoForm',
        'solicitarCodigoCorreo', 'showResetPasswordForm', 'showtokenInvalid', 'registroCliente', 'vistaCarritoDeCompras']);
    }

    public function showLoginFormUsuario()
    {
        if (Auth::guard('web')->check()) {
            return redirect()->route('user.index');
        }

        return view('frontend.login.vistalogin');
    }

    public function loginUsuario(Request $request){

        $rules = [
            'email'    => ['required'],
            'password'   => ['required'],
        ];

        $attributes = [
            'email'   => __('meta.contact_v12'), // el correo es requerido
            'password' => __('meta.contact_v14'), // la contraseña es requerida
        ];

        $validator = Validator::make($request->all(), $rules, [], $attributes);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => __('meta.unknown_error'),
            ]);
        }

        $credentials = $request->only('email', 'password');

        // Guard admin (usa el provider 'admin' del auth.php)
        if (Auth::guard('web')->attempt($credentials)) {

            // Regenera la sesión por seguridad
            $request->session()->regenerate();

            // Puedes redirigir o devolver JSON
            return response()->json([
                'success' => 1,
                'ruta' => route('user.index'),
                'admin' => Auth::guard('web')->user(),
            ]);
        }

        return ['success' => 2, 'message' => __('meta.incorrect_data')];
    }

    public function logoutUsuario(Request $request)
    {
        Auth::guard('web')->logout();
        $request->session()->regenerateToken();
        return redirect()->route('user.index');
    }


    public function showIngresarCorreoForm()
    {
        return view('frontend.login.vistaingresarcorreo');
    }

    public function solicitarCodigoCorreo(Request $request)
    {
        $rules = [
            'email' => ['required', 'email:rfc,dns'],
        ];
        $attributes = [
            'email' => __('meta.contact_v12'),
        ];

        $validator = Validator::make($request->all(), $rules, [], $attributes);

        if ($validator->fails()) {
            return response()->json([
                'success' => 0,
                'message' => __('meta.unknown_error'),
            ]);
        }

        // 2) (Opcional) Rate limit por IP + email para evitar abuso
        $key = 'pwd-reset:' . Str::lower($request->input('email')) . '|' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 25)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'success' => 1, // muchos intentos
                'message' => __('meta.too_many_attempts', ['seconds' => $seconds]), // crea esta key si quieres
            ]);
        }

        // 🔍 Buscar el usuario
        $user = Usuario::where('email', $request->email)->first();

        if (! $user) {
            RateLimiter::hit($key, 60);
            return response()->json([
                'success' => 2, // correo no encontrado
                'message' => __('meta.email_not_found'),
            ]);
        }

        // 🧩 Crear token de recuperación
        $token = Password::broker('users')->createToken($user);

        // 🔗 Generar URL personalizada de reseteo
        $resetUrl = route('user.password.reset.form', ['token' => $token, 'email' => $user->email]);

        // ✉️ Enviar correo personalizado
        Mail::to($user->email)->send(new PasswordResetMail($user, $resetUrl));

        return response()->json([
            'success' => 3,
            'message' => __('meta.reset_link_sent'), // enviado
        ]);
    }


    public function showResetPasswordForm(Request $request, $token)
    {

        $email  = request('email');
        $broker = Password::broker('users'); // <-- tu broker para admins
        $user   = $broker->getUser(['email' => $email]);

        $tokenIsValid = $user && (
            method_exists($broker, 'tokenExists')
                ? $broker->tokenExists($user, $token)
                : $broker->getRepository()->exists($user, $token)
            );

        if (!$tokenIsValid) {
            return redirect()
                ->route('user.token.novalid');
        }

        return view('frontend.login.vistaresetpassword', compact('token', 'email'));
    }


    public function showtokenInvalid()
    {
        return view('frontend.login.vistatokennovalido');
    }

    public function registroCliente(Request $request)
    {
        $regla = array(
            'name' => 'required',
            'email' => 'required',
            'password' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        // 2) Normalizar datos
        $name  = trim($request->input('name'));
        $email = trim(mb_strtolower($request->input('email')));
        $pass  = $request->input('password');

        // 3) Crear usuario + login dentro de transacción
        try {
            DB::beginTransaction();

            if(Usuario::where('email', $email)->exists()){
                return ['success' => 1];
            }

            $fechaActual = Carbon::now('America/El_Salvador');

            $user = Usuario::create([
                'nombre'     => $name,
                'email'    => $email,
                'password' => Hash::make($pass),
                'fecha_registro' => $fechaActual
            ]);

            Auth::guard('web')->login($user);
            $request->session()->regenerate();

            DB::commit();

            // 4) Redirección (elige tu destino)
            // Opción dashboard:
            $ruta = route('user.index');

            return response()->json([
                'success' => 2,
                'ruta'    => $ruta,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::info($e->getMessage());
            // error generico
            return ['success' => 99];
        }
    }


















    // @Auth
    public function vistaMisOrdenes()
    {
        $user = Auth::guard('web')->user();

        if (!$user) {
            return redirect()->route('login');
        }

        $orders = Ordenes::where('id_usuario', $user->id)
            ->where('visible_cliente', 1)
            ->orderByDesc('fecha')
            ->paginate(10)
            ->through(function ($order) use ($user) {
                // Formato de fecha según país
                if ($user->id_paises == 1) {
                    // Formato latino (Ej: 08/11/2025 04:30 PM)
                    $order->fecha_formateada = $order->fecha
                        ? Carbon::parse($order->fecha)->format('d/m/Y')
                        : null;
                } else {
                    // Formato USA (Ej: 11/08/2025 04:30 PM)
                    $order->fecha_formateada = $order->fecha
                        ? Carbon::parse($order->fecha)->format('m/d/Y')
                        : null;
                }

                // Monto y estado formateados
                $order->monto_formateado = number_format($order->total, 2);


                // === ESTADO traducido ===
                // 1) Obtener nombre interno: pending, paid, etc.
                $statusName = Ordenes::STATUS[$order->status_id] ?? '';

                // 2) Buscar traducción del meta
                $order->estado_texto = __('meta.order_status_' . $statusName);

                return $order;
            });



        return view('frontend.dashboard.vistaordenes', compact('orders'));
    }









    public function vistaMisDirecciones()
    {
        $userId = Auth::guard('web')->id();

        $arrayDirecciones = Direcciones::where('id_usuario', $userId)
            ->get()
            ->map(function ($item) {

                $infoPais = Pais::find($item->id_paises);
                $infoDep  = Departamento::find($item->id_departamento);
                $infoMun  = Municipio::find($item->id_municipio);

                $item->nombrePais = $infoPais->nombre ?? '';
                $item->nombreDep  = $infoDep->nombre ?? '';
                $item->nombreMun  = $infoMun->nombre ?? '';

                // --- Construir texto según el país ---
                switch ($item->id_paises) {
                    case 1:
                        // El Salvador → país 1
                        $item->textoDireccion = "
                        <b>País:</b> {$item->nombrePais}<br>
                        <b>Departamento:</b> {$item->nombreDep}<br>
                        <b>Municipio:</b> {$item->nombreMun}<br>
                        <b>Dirección:</b> {$item->direccion}
                    ";
                        break;

                    case 2:
                        // Ej. Estados Unidos
                        $item->textoDireccion = "
                        <b>País:</b> {$item->nombrePais}<br>
                        <b>Departamento / Estado:</b> {$item->nombreDep}<br>
                        <b>Dirección:</b> {$item->direccion}
                    ";
                        break;

                    default:
                        // Otros países
                        $item->textoDireccion = "
                        <b>País:</b> {$item->nombrePais}<br>
                        <b>Dirección:</b> {$item->direccion}
                    ";
                        break;
                }

                return $item;
            });

        return view('frontend.dashboard.vistamisdirecciones', [
            'arrayDirecciones' => $arrayDirecciones,
        ]);
    }



    public function vistaNuevaDireccion()
    {
        // Países activos y disponibles
        $paises = DB::table('paises')
            ->where('activo', 1)
            ->select('id', 'nombre')
            ->get();

        // Departamentos solo de El Salvador y USA
        $departamentos = DB::table('departamentos')
            ->whereIn('id_paises', [1, 2])
            ->where('activo', 1)
            ->select('id', 'id_paises', 'nombre')
            ->get();

        // Municipios solo de El Salvador
        $municipios = DB::table('municipios')
            ->where('activo', 1)
            ->select('id', 'id_departamentos', 'nombre')
            ->get();

        return view('frontend.dashboard.vistanuevadireccion', [
            'paises' => $paises,
            'departamentos' => $departamentos,
            'municipios' => $municipios,
        ]);
    }


    public function vistaEditarDireccion($iddireccion)
    {
        try {
            $userId  = auth()->id();

            $address = Direcciones::where('id', $iddireccion)
                ->where('id_usuario', $userId)
                ->firstOrFail();

            // === mismos catálogos que en "nuevo" ===
            $paises = DB::table('paises')
                ->where('activo', 1)->where('disponible', 1)
                ->select('id','nombre')->get();

            $departamentos = DB::table('departamentos')
                ->whereIn('id_paises', [1,2])
                ->where('activo', 1)->where('disponible', 1)
                ->select('id','id_paises','nombre')->get();

            $municipios = DB::table('municipios')
                ->where('activo', 1)->where('disponible', 1)
                ->select('id','id_departamentos','nombre')->get();

            return view('frontend.dashboard.vistaeditardireccion', [
                'mode'          => 'edit',
                'address'       => $address,
                'paises'        => $paises,
                'departamentos' => $departamentos,
                'municipios'    => $municipios,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->view('errors.404', [], 404);
        }
    }








    // guardar nueva direccion
    public function guardarNuevaDireccion(Request $request)
    {
        $regla = array(
            'pais' => 'required',
            'nombre' => 'required',
            'telefono' => 'required',
        );

        $validar = Validator::make($request->all(), $regla);

        if ($validar->fails()){ return ['success' => 0];}

        DB::beginTransaction();
        try {

            // guardar Direccion
            $userId = Auth::guard('web')->id();

            // Verificar si el usuario ya tiene direcciones
            $tieneDireccion = Direcciones::where('id_usuario', $userId)->exists();

            // Si no tiene, esta será la predeterminada
            $esPredeterminada = $tieneDireccion ? 0 : 1;

            Log::info("VALORR");
            Log::info($esPredeterminada);



            Direcciones::create([
                'id_usuario'         => $userId,
                'id_paises'          => $request->pais,
                'id_departamento'    => $request->departamento,
                'id_municipio'       => $request->municipio,
                'nombre'             => strip_tags($request->nombre),
                'direccion'          => strip_tags($request->direccion),
                'direccion_opcional' => strip_tags($request->direccion_opcional),
                'ciudad'             => strip_tags($request->ciudad),
                'estado'             => strip_tags($request->provincia),
                'zipcode'            => strip_tags($request->postal),
                'telefono'           => strip_tags($request->telefono),
                'predeterminado'     => $esPredeterminada,
            ]);


            // Guardar una direccion de facturacion del cliente, 1 sola vez
            $existe = DireccionFacturacion::where('id_usuario', $userId)->exists();

            if (!$existe) {
                DireccionFacturacion::create([
                    'id_usuario'   => $userId,
                    'id_paises'    => $request->pais,
                    'nombre'       => $request->nombre,
                    'direccion'    => $request->direccion,
                    'ciudad'       => $request->ciudad,
                    'estado'       => $request->provincia,
                    'zipcode'      => $request->postal,
                    'telefono'     => $request->telefono,
                ]);
            }

            DB::commit();
            $ruta = route('user.address');

            return response()->json([
                'success' => 1,
                'ruta'    => $ruta,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e->getMessage());
            return ['success' => 0, 'message' => __('meta.error_updating')];
        }
    }

    public function borrarDireccionCliente(Request $request)
    {
        $request->validate([
            'id' => ['required', 'integer']
        ]);

        $userId = Auth::guard('web')->id();

        // Buscar la dirección y asegurar que sea del usuario
        $dir = Direcciones::where('id', $request->id)
            ->where('id_usuario', $userId)
            ->first();

        if (!$dir) {
            return response()->json(['success' => 0,
                'msg' => __('meta.not_found_or_does')]);
        }

        try {
            DB::beginTransaction();

            $eraPredeterminada = (int)($dir->predeterminado ?? 0) === 1;

            // Borrar la dirección
            $dir->delete();

            // Si la borrada era predeterminada, marcar otra como predeterminada (si existe)
            if ($eraPredeterminada) {
                $otra = Direcciones::where('id_usuario', $userId)
                    ->orderBy('id', 'asc') // o created_at, según tu modelo
                    ->first();

                if ($otra) {
                    $otra->predeterminado = 1;
                    $otra->save();
                }
            }

            DB::commit();

            return ['success' => 1];
        } catch (\Throwable $e) {
            DB::rollBack();
            // Log::error($e->getMessage());
            return response()->json(['success' => 0]);
        }
    }


    public function direccionPorDefault(Request $request)
    {
        try {
            // Validar datos recibidos
            $request->validate([
                'id' => 'required|integer|exists:direcciones,id',
            ]);

            // Obtener usuario actual
            $userId = auth()->id();

            // Buscar dirección seleccionada
            $direccion = Direcciones::where('id', $request->id)
                ->where('id_usuario', $userId)
                ->first();

            if (!$direccion) {
                return response()->json(['success' => 0,
                    'message' => __('meta.unknown_error')]);
            }

            // Quitar el default anterior del mismo usuario
            Direcciones::where('id_usuario', $userId)
                ->update(['predeterminado' => 0]);

            // Marcar la nueva como default
            $direccion->predeterminado = 1;
            $direccion->save();

            return response()->json([
                'success' => 1,
                'message' => __('meta.address_set_default')
            ]);

        } catch (\Exception $e) {
            Log::error('Error al establecer dirección predeterminada: ' . $e->getMessage());
            return response()->json([
                'success' => 0,
                'message' => __('meta.unknown_error')
            ]);
        }
    }



    public function actualizarDireccion(Request $request)
    {
        try {
            DB::beginTransaction();

            $userId    = Auth::id();
            $addressId = $request->input('address_id');

            // Buscar dirección del usuario
            $address = Direcciones::where('id', $addressId)
                ->where('id_usuario', $userId)
                ->firstOrFail();

            // Actualizar campos básicos
            $address->id_paises       = $request->pais;
            $address->id_departamento = $request->departamento ?: null;
            $address->id_municipio    = $request->municipio ?: null;
            $address->nombre            = trim(strip_tags($request->nombre));
            $address->direccion         = trim(strip_tags($request->direccion));
            $address->direccion_opcional= trim(strip_tags($request->direccion_opcional));
            $address->ciudad            = trim(strip_tags($request->ciudad));
            $address->estado            = trim(strip_tags($request->provincia)); // input "provincia"
            $address->zipcode           = trim(strip_tags($request->postal));    // input "postal"
            $address->telefono          = trim(strip_tags($request->telefono));

            $address->save();

            DB::commit();

            return response()->json([
                'success' => 1,
                'ruta'    => route('user.address'),
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => 0,
                'message' => __('meta.unknown_error'),
            ]);
        }
    }


    public function vistaPerfil()
    {
        try {
            $userId  = auth()->id();

            $infoUsuario = Usuario::where('id', $userId)
                ->firstOrFail();

            $direccionFactura = DireccionFacturacion::where('id_usuario', $userId)->first();

            $paises = DB::table('paises')
                ->where('activo', 1)
                ->select('id', 'nombre')
                ->get();

            return view('frontend.dashboard.vistaperfil', [
                'paises' => $paises,
                'infouser'       => $infoUsuario,
                'arrayDireccionFactura' => $direccionFactura,
            ]);
        } catch (ModelNotFoundException $e) {
            return response()->view('errors.404', [], 404);
        }
    }



    public function actualizarPerfil(Request $request)
    {

        $userId = Auth::id();

        // 1) Validación: todos los campos opcionales
        $data = $request->validate([
            'pais'          => ['nullable','integer','exists:paises,id'],
            'nombre'        => ['nullable','string','max:50'],
            'direccion'     => ['nullable','string','max:100'],
            'ciudad'        => ['nullable','string','max:50'],
            'estado'        => ['nullable','string','max:50'],
            'codigo_postal' => ['nullable','string','max:20'],
            'telefono'      => ['nullable','string','max:20'],
        ]);

        try {
            return DB::transaction(function () use ($userId, $data) {

                // 2) Crea o actualiza el único registro por usuario
                DireccionFacturacion::updateOrCreate(
                    ['id_usuario' => $userId],
                    [
                        'id_usuario'    => $userId,
                        'id_paises'     => $data['pais'] ?? null,
                        'nombre'        => $data['nombre'] ?? null,
                        'direccion'     => $data['direccion'] ?? null,
                        'ciudad'        => $data['ciudad'] ?? null,
                        'estado'        => $data['estado'] ?? null,
                        'zipcode'       => $data['codigo_postal'] ?? null,
                        'telefono'      => $data['telefono'] ?? null,
                    ]
                );

                return response()->json([
                    'success' => 1,
                    'message' => __('meta.saved_successfully'),
                ]);
            });
        } catch (\Throwable $e) {
            Log::error('Actualizar perfil (facturación) falló', [
                'user_id' => $userId,
                'msg'     => $e->getMessage(),
            ]);

            return response()->json([
                'success' => 0,
                'message' => __('meta.unknown_error'),
            ], 500);
        }
    }



    public function vistaCarritoDeCompras()
    {
        $cart = $this->cart();
        $content = $cart->getContent(); // CartCollection

        if ($content->isEmpty()) {
            $items = [];
            $subtotal = 0;
            return view('frontend.pages.cart', compact('items','subtotal'));
        }

        // Reunir product_ids
        $productIds = [];
        $presIds    = [];

        foreach ($content as $row) {
            // ID puede venir “15:3” o “15”
            $parts = explode(':', (string)$row->id);
            $pid = (int)($row->attributes->get('product_id') ?? $parts[0] ?? 0);
            if ($pid) $productIds[] = $pid;

            $pres = (int)($row->attributes->get('presentacion_id') ?? ($parts[1] ?? 0));
            if ($pres) $presIds[] = $pres;
        }

        $productos = Producto::whereIn('id', array_unique($productIds))
            ->where('activo',1)->get()->keyBy('id');

        $presentaciones = $presIds
            ? ProductosPresentacion::whereIn('id', array_unique($presIds))
                ->where('activo',1)->get()->keyBy('id')
            : collect();

        $items = [];
        $subtotal = 0;

        foreach ($content as $row) {
            $lineId = (string)$row->id;
            $parts  = explode(':', $lineId);

            $pid  = (int)($row->attributes->get('product_id') ?? $parts[0] ?? 0);
            $pres = (int)($row->attributes->get('presentacion_id') ?? ($parts[1] ?? 0));
            $qty  = (int)$row->quantity;

            $prod = $productos->get($pid);
            if (!$prod || $qty <= 0) continue;

            // Recalcular nombre y precio por seguridad
            $rcProd = getRegionContent($prod->content_key);
            $tituloProd = $rcProd['title'] ?? ($row->name ?? 'Producto');

            $price = (float)($prod->precio ?? $row->price ?? 0);
            $tituloPres = null;

            if ($pres && $presentaciones->has($pres)) {
                $presObj = $presentaciones->get($pres);
                $rcPres  = getRegionContent($presObj->content_key);
                $tituloPres = $rcPres['title'] ?? null;
                if (!is_null($presObj->precio)) $price = (float)$presObj->precio;
            }

            $name = $tituloPres ? "{$tituloProd} — {$tituloPres}" : $tituloProd;
            $rowTotal = $price * $qty;
            $subtotal += $rowTotal;

            $imageUrl = $prod->imagen
                ? asset('storage/archivos/' . ltrim($prod->imagen, '/'))
                : asset('images/placeholder.png');

            $items[] = [
                'row_id'    => $lineId, // <-- Usaremos este para update/remove
                'product_id'=> $pid,
                'pres_id'   => $pres ?: null,
                'name'      => $name,
                'slug'      => $rcProd['slug'] ?? null,
                'image'     => $imageUrl,
                'price'     => $price,
                'qty'       => $qty,
                'row_total' => $rowTotal,
            ];
        }

        return view('frontend.pages.cart', compact('items','subtotal'));
    }





}
