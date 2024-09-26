<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Venta;
use App\Detalle;

class VentaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return response()->json(['status'=>'ok','data'=>Venta::all()], 200);
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
        if (!$request->input('fecha') || !$request->input('IdCliente') )
		{
			return response()->json(['errors'=>array(['code'=>422,'message'=>'Faltan datos necesarios para el alta.'])],422);
		}

        $venta=Venta::create($request->all());

         return response()->json(['data'=>$venta], 201)
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
        $venta=Venta::where( 'Id_Venta', $id)->with('cliente')->get() ;

        if (!$venta)
		{
			return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra Venta'])],404);
		}
        
        return response()->json(['status'=>'ok','data'=>$venta],200);
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
        //$venta=Venta::find($id);
        $venta=Venta::where( 'Id_Venta', $id)->with('cliente')->get() ;
      
        $cambioEnCampo = 0;
        if (!$venta)
        {
            return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra Venta.'])],404);
        }

        $fecha = $request->input('fecha');
        $idCliente = $request->input('IdCliente');
        $iva = $request->input('iva');
        $descuento = $request->input('descuento');
        $total = $request->input('total');

        if($fecha){      $venta->fecha = $fecha;         $cambioEnCampo= 1;   }
        if($idCliente){  $venta->IdCliente = $idCliente; $cambioEnCampo= 1;   }
        if($iva){        $venta->iva = $iva;             $cambioEnCampo= 1;   }
        if($descuento){  $venta->descuento = $descuento; $cambioEnCampo= 1;   }
        if($total){      $venta->total = $total;         $cambioEnCampo= 1;   }
 
        if($cambioEnCampo){
            
            $venta->save();
            
            return response()->json(['status'=>'ok','data'=>$venta], 200);
        }else{
            
            return response()->json(['errors'=>array(['code'=>304,'message'=>"No se ha modificado ningún dato. $iva $descuento $total"])],304);
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
        $venta=Venta::find($id);
        
        if (!$venta)
        {
            return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra.'])],404);
        }

        $detalle = $venta->Detalle;
        $cliente = $venta->Cliente;
        

        if ( sizeof($detalle) > 0)
        {
            return response()->json(['errors'=>array(['code'=>404,'message'=>'No se puede borrar tiene registros en detalle '])],404);
        }

        $venta->delete();
        
        
        return response()->json(['code'=>204,'message'=>'Se ha eliminado'],204);

    }
    public function listar(){
        return response()
        ->json([
                'status'=>'ok',
                'data'=>   Venta::with('cliente')->get() 
            ], 200);        
    }
    public function recalcula($idVenta){

        $detalle =  Detalle::where('IdVenta', $idVenta)->get();
        $venta = Venta::find($idVenta);
        $suma = 0;
        
        foreach ($detalle as $det) {
            $precio = (float)$det->producto->precio;
            $can = (float)$det->cantidad;
            $subtotal = $precio*$can;
            $suma += $subtotal; 
            
        }

        $iva = $suma * 0.15 ;
        $descuento = 0.0;
        if ( $venta->descuento > 0 ){
            $descuento = ($venta->descuento/100) * $suma;
            
        }
        
        $total = $iva + $suma - $descuento;

        $venta->iva = $iva;
        $venta->total = $total;
        

        $venta->save();

        return response()->json(['datos'=> $venta ],202);


        $detalle =  Detalle::where('IdVenta', $idVenta)->with('producto')->get();

    }

}
