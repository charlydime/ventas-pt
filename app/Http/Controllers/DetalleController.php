<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Detalle;

class DetalleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        return response()->json(['status'=>'ok','data'=>$detalle=Detalle::with('producto')->get() ], 200);
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
        
         if (!$request->input('IdProducto') || 
             !$request->input('IdVenta') ||
             !$request->input('cantidad')
             )
		{
			return response()->json(['errors'=>array(['code'=>422,'message'=>'Faltan datos necesarios para el alta.'])],422);
		}
        
        $detalle=Detalle::create($request->all());

        $this->recalcula($request->input('IdVenta'));
        
        //  $detalle=Detalle::create([
        //    'IdProducto' => $request->input('IdProducto'),
        //      'IdVenta' => $request->input('IdVenta'),
        //      'cantidad' =>  $request->input('cantidad')
        //   ]);

         return response()->json(['data'=>$detalle], 201)
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
         //$detalle=Detalle::find($id);
         $detalle=Detalle::where( 'Id_Detalle', $id)->with('producto')->get() ;

        if (!$detalle)
		{
			return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra Venta'])],404);
		}
        
        return response()->json(['status'=>'ok','data'=>$detalle],200);
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
        $detalle=Detalle::find($id);
        //$detalle=Detalle::where( 'Id_Detalle', $id)->with('producto')->get() ;
        $cambioEnCampo = false;
        if (!$detalle)
        {
            return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra Venta.'])],404);
        }

        $idVenta = $request->input('IdVenta');
        $idProducto = $request->input('IdProducto');
        $cantidad = $request->input('cantidad');
        $subtotal = $request->input('subtotal');

        $detalle->IdVenta = $idVenta;       
        $detalle->IdProducto = $idProducto; 
        $detalle->cantidad = $cantidad;     
        $detalle->subtotal = $subtotal;     
        
        if($idVenta){      $cambioEnCampo= true;   }
        if($idProducto){   $cambioEnCampo= true;   }
        if($cantidad){     $cambioEnCampo= true;   }
        if($subtotal){     $cambioEnCampo= true;   }
        

        if($cambioEnCampo){
            $detalle->save();
            return response()->json(['status'=>'ok','data'=>$detalle], 200);
        }else{
            return response()->json(['errors'=>array(['code'=>304,'message'=>'No se ha modificado ningún dato.'])],304);
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
        $detalle=Detalle::find($id);
        
        if (!$detalle)
        {
            return response()->json(['errors'=>array(['code'=>404,'message'=>'No se encuentra.'])],404);
        }

        //$producto = $detalle->Producto;
        //$venta = $detalle->Venta;
         
        $detalle->delete();
       
        return response()->json(['code'=>204,'message'=>'Se ha eliminado'],204);

        
    }

    public function listar($id){
        return response()
        ->json([
                'status'=>'ok',
                'data'=>   Detalle::where('IdVenta', $id)->with('producto')->get() 
            ], 200);        
    }

    public function recalcula($idVenta){

        $detalle =  Detalle::where('IdVenta', $idVenta)->with('producto')->get();
        $precio = 0 ;
        $can = 0;
        foreach ($detalle as $det) {
            $precio = (float)$det->producto->precio;
            $can = (float)$det->cantidad;
            $subtotal = $precio*$can;
                        
            $detalleNuevo = Detalle::find($det->Id_Detalle); 

            $detalleNuevo->subtotal = $subtotal;
            $detalleNuevo->save();
        }

        $detalle =  Detalle::where('IdVenta', $idVenta)->with('producto')->get();
        return response()->json(['datos'=> $detalle ],202);

    }
}
