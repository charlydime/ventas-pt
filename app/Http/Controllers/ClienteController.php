<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Cliente;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return response()->json(['status'=>'ok','data'=>Cliente::all()], 200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
         if (!$request->input('nombre') || !$request->input('Estado') )
		{
			return response()->json(['errors'=>array(['code'=>422,'message'=>'Faltan datos necesarios para el alta.'])],422);
		}

        $cliente=Cliente::create($request->all());

         return response()->json(['data'=>$cliente], 201)
         //->header('Location',  url('/front/').'/venta/'.$venta->id)
         ;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
        $cliente=Cliente::find($id);

        if (!$cliente)
		{
			return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra cliente'])],404);
		}
        
        return response()->json(['status'=>'ok','data'=>$cliente],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $cliente=Cliente::find($id);
      
        $cambioEnCampo = 0;
        if (!$cliente)
        {
            return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra Venta.'])],404);
        }

        $nombre = $request->input('nombre');
        $estado = $request->input('Estado');
        

        if($nombre){   $cliente->nombre = $nombre;     $cambioEnCampo= 1;   }
        if($estado){   $cliente->Estado = $estado;     $cambioEnCampo= 1;   }
               
        if($cambioEnCampo){
 
            $cliente->save();
            return response()->json(['status'=>'ok','data'=>$cliente], 200);
        }else{
            
            return response()->json(['errors'=>array(['code'=>304,'message'=>"No se ha modificado ningún dato."])],304);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
          $cliente=Producto::find($id);
        
        if (!$cliente)
        {
            return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra.'])],404);
        }

        
        $cliente->delete();
        
        return response()->json(['code'=>204,'message'=>'Se ha eliminado'],204);
    }

    public function activos(){

         return response()->json(['status'=>'ok',
         'data'=>Cliente::where('Estado', 'activo')->get()]
         , 200);
    }

}
