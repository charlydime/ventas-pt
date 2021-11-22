<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Producto;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return response()->json(['status'=>'ok','data'=>Producto::all()], 200);
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
         if (!$request->input('nombre') || !$request->input('precio') || !$request->input('Cantidad') )
		{
			return response()->json(['errors'=>array(['code'=>422,'message'=>'Faltan datos necesarios para el alta.'])],422);
		}

        $producto=Producto::create($request->all());

         return response()->json(['data'=>$producto], 201)
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
        $producto=Producto::find($id);

        if (!$producto)
		{
			return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra Producto'])],404);
		}
        
        return response()->json(['status'=>'ok','data'=>$producto],200);
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
        $producto=Producto::find($id);
      
        $cambioEnCampo = 0;
        if (!$producto)
        {
            return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra Venta.'])],404);
        }

        $nombre = $request->input('nombre');
        $precio = $request->input('precio');
        $cantidad = $request->input('Cantidad');
        

        if($nombre){   $producto->nombre = $nombre;     $cambioEnCampo= 1;   }
        if($precio){   $producto->precio = $precio;     $cambioEnCampo= 1;   }
        if($cantidad){ $producto->cantidad = $cantidad; $cambioEnCampo= 1;   }
        
        if($cambioEnCampo){
            
            $producto->save();
            return response()->json(['status'=>'ok','data'=>$producto], 200);
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
         $producto=Producto::find($id);
        
        if (!$producto)
        {
            return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra.'])],404);
        }

        
        $producto->delete();
        
        return response()->json(['code'=>204,'message'=>'Se ha eliminado'],204);

    }
    public function activos(){

         return response()->json(['status'=>'ok',
         'data'=>Producto::where('Cantidad', '>',0)->get()]
         , 200);
    }
}
