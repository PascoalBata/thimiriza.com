<?php

namespace App\Http\Controllers\Utilizador;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUtilizadorRequest;
use App\Models\Utilizador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UtilizadorController extends Controller
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
        //$this->middleware('auth')->only(['update', 'destroy', 'edit', 'show', 'index']);
        //$this->middleware('auth')->except(['create', 'store']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        //$empresas = Empresa::paginate(2);
        //$empresas = DB::table('empresas')->orderByRaw('updated_at - created_at DESC')->get('empresa_id');
        //return view('pt.Admin.pages.empresas', ['empresas' => $empresas]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('pt.Empresa.pages.registar_empresa');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreEmpresaRequest $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreUtilizadorRequest $request)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \App\Http\Requests\StoreEmpresaRequest $request
     * @return \Illuminate\Http\Response
     */
    public function storeAdmin($id_empresa, $nome, $email, $telefone, $endereco, $senha)
    {
        //
        //$empresa_dados = $request->all();

        $utilizador = new Utilizador;
        $utilizador->utilizador_id = $id_empresa . '/' . 'Admin';
        $utilizador->utilizador_nome  = $nome;
        $utilizador->utilizador_apelido = '-';
        $utilizador->utilizador_telefone  = $telefone;
        $utilizador->utilizador_endereco  = $endereco;
        $utilizador->utilizador_email  = $email;
        $utilizador->utilizador_nascimento = now();
        $utilizador->utilizador_previlegio = 'TOTAL';
        $utilizador->utilizador_senha = bcrypt($senha);
        $utilizador->id_empresa = $id_empresa;
        if(!$utilizador->save()){
            return redirect()->route('raiz')->with('status', 'O registo falhou do utilizador (Admin) falhou! Por favor, tente novamente.');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function new_utilizador_id($id_empresa)
    {
        $utilizadores_id = DB::table('utilizadores')->where('id_empresa', 'like', $id_empresa)->orderByRaw('created_at DESC')->first();
        if (DB::table('utilizadores')->count() == 0) {
            return redirect()->route('raiz')->with('status', 'O registo do utilizador (Admin_ID) para a empresa falhou! Por favor, tente novamente.');
        }
        if (DB::table('utilizadores')->count() == 1) {
            return $this->next_id('');
        }
        $utilizador_id = $utilizadores_id->utilizador_id;
        return $this->next_id($utilizador_id);
        //return view('pt.Admin.pages.teste', ['empresas' => $empresa_id]);
    }

    private function next_id($last)
    {
        $empresa_id = substr($last, 0, 5);
        $new_id = $empresa_id . "/" . "001";
        if ($last == "") {
            return $new_id;
        }
        $last = substr($last, 6, 3);
        $last++;
        $new_id = $empresa_id . "/" . $last;
        return $new_id;
    }
}
