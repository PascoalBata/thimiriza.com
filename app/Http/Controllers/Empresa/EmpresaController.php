<?php

namespace App\Http\Controllers\Empresa;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmpresaRequest;
use App\Models\Empresa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EmpresaController extends Controller
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
        $empresas = Empresa::paginate(2);
        //$empresas = DB::table('empresas')->orderByRaw('updated_at - created_at DESC')->get('empresa_id');
        return view('pt.Admin.pages.empresas', ['empresas' => $empresas]);
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
    public function store(StoreEmpresaRequest $request)
    {
        //
        //$empresa_dados = $request->all();
        $pacote_id = '1'; 
        $empresa_estado = "ON";
        $empresa_id = $this->new_empresa_id(); 
        $empresa_nome = $request->input('empresa_nome');
        $empresa_email = $request->input('empresa_email');
        $empresa_telefone = $request->input('empresa_telefone');
        $empresa_endereco = $request->input('empresa_endereco');
        $empresa_tipo = $request->input('empresa_tipo');
        $empresa_senha = $request->input('empresa_senha');
        $empresa_nuit = $request->input('empresa_nuit');

        $empresa = new Empresa;
        $empresa->empresa_id = $empresa_id;
        $empresa->empresa_nome  = $empresa_nome;
        $empresa->empresa_tipo  = $empresa_tipo;
        $empresa->empresa_nuit  = $empresa_nuit;
        $empresa->empresa_telefone  = $empresa_telefone;
        $empresa->empresa_endereco  = $empresa_endereco;
        $empresa->empresa_email  = $empresa_email;
        $empresa->empresa_estado  = $empresa_estado;
        $empresa->id_pacote = $pacote_id;
        $empresa->save();
        //$empresa->empresa_  = $empresa_;
        return view('pt.Login.pages.login');
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

    public function new_empresa_id()
    {
        $empresas_id = DB::table('empresas')->orderByRaw('updated_at - created_at DESC')->first();
        if (DB::table('empresas')->count() == 0) {
            return $this->next_id('');
        }
        $empresa_id = $empresas_id->empresa_id;
        return $this->next_id($empresa_id);
        //return view('pt.Admin.pages.teste', ['empresas' => $empresa_id]);
    }

    private function next_id($last)
    {
        $new_id = "A0001";
        if ($last == "") {
            return $new_id;
        }
        $last++;
        $new_id = $last;
        if (substr($last, 1, 4) == "0000") {
            $letters = substr($last, 0, 1);
            $numbers = "0001";
            $new_id = $letters . $numbers;
        }
        return $new_id;
    }
}
