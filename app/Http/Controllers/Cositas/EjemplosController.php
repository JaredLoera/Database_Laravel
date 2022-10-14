<?php

namespace App\Http\Controllers\Cositas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\chofere;
use Illuminate\Support\Facades\Log;
use Illuminate\Database\QueryException;

class EjemplosController extends Controller
{
public function index(Request $request){
    try {
        $choferes = chofere::select()->get();
    return  response()->json(
        [
            "Tabla"=>"choferes",
           "Resultados"=>$choferes
        ]
    );
    } catch (QueryException $er) {
        Log::channel('slack')->error($er);
        return response()->json([
            "Error"=>$er
        ],400);
        
    }   
}
public function insertar(Request $request){
    $validator = Validator::make($request->all(), [
        'Nombre' => 'required|string',
        'Ap_paterno' => 'required|string',
        "Ap_materno" => 'required|string',
        "Estado" => 'required|boolean'
    ]);
    if ($validator->fails()) {
        return response()->json([
          "ERRORES"=>  $validator->errors()
        ],400);
    } 
    try {
        //por error mio tuve que usar lenguaje inclusivo JAJAJAJAJA
        $choferes = new chofere();
        $choferes->nombre =$request->Nombre;
        $choferes->ap_paterno =$request->Ap_paterno;
        $choferes->ap_materno =$request->Ap_materno;
        $choferes->estado =$request->Estado;
        $choferes->save();
        
    } catch (QueryException $th) {
       return response()->json(["WEY UN ORROR TU COSA"=>$th],400);
    }
    return response()->json(["Info"=>[
        "MESSAGE"=>"se a insertado correctamente",
        "El nombre era"=>$request->Nombre,
        "El paterno era"=>$request->Ap_paterno,
        "El matenro era"=>$request->Ap_materno,
        "EL estado era" => $request->Estado
    ]],201);
}
} 
