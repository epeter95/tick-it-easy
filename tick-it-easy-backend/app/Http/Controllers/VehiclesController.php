<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vehicle;
use App\Rules\VehicleType;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class VehiclesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Vehicle::all();
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
        $vehicle = new Vehicle;
        $types = ['busz','villamos','repülő'];
        if(!$request->exists('type') || !$request->exists('license') || !$request->exists('capacity')){
            return response()->json(['success'=>false,'message'=>'The type (busz,villamos,repülő) license (date: Y-m-d), capacity(integer) field is required']);
        }
        else if(!in_array($request->input('type'),$types)){
            return response()->json(['success'=>false,'message'=>'The type field must be one of the values: busz|villamos|repülő']);
        }
        else if(!$this->validateDate($request->input('license'))){
            return response()->json(['success'=>false,'message'=>'The license field must be valid datetime (YYYY-mm-dd)']);
        }
        else if(!is_numeric($request->input('capacity'))){
            return response()->json(['success'=>false,'message'=>'The capacity field must be a number']);
        }
        else{
            $vehicle->type = $request->input('type');
            $vehicle->license = Carbon::createFromFormat('Y-m-d', $request->input('license'));
            $vehicle->capacity =  intval($request->input('capacity'));
            $vehicle->save();
            return response()->json([
                'success'=>true,
                'message'=>'A new vehicle is created for type: '
                                    .$request->input('type').
                                    ', license: '.$request->input('license').
                                    ', capacity: '.$request->input('capacity')]);
        }
    }

    function validateDate($date){
        if(preg_match('/\d{4}-\d{2}-\d{2}/',$date)){
            $month = explode('-',$date)[1];
            $day = explode('-',$date)[2];
            if(intval($month)>12){
                return false;
            }
            elseif(intval($day)>31){
                return false;
            }else{
                return true;
            }
        }else{
            return false;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Vehicle::where('id',$id)->first();
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
        $vehicle = Vehicle::where('id',$id)->first();
        $types = ['busz','villamos','repülő'];
        if($vehicle==null){
            return response()->json(['success'=>false,'message'=>'The id='.$id.' Vehicle isn\'t exist in the database']);
        }
        else if(!$request->exists('type') || !$request->exists('license') || !$request->exists('capacity')){
            return response()->json(['success'=>false,'message'=>'The type (busz,villamos,repülő) license (date: Y-m-d), capacity(integer) field is required']);
        }
        else if(!in_array($request->input('type'),$types)){
            return response()->json(['success'=>false,'message'=>'The type field must be one of the values: busz|villamos|repülő']);
        }
        else if(!$this->validateDate($request->input('license'))){
            return response()->json(['success'=>false,'message'=>'The license field must be valid datetime (YYYY-mm-dd)']);
        }
        else if(!is_numeric($request->input('capacity'))){
            return response()->json(['success'=>false,'message'=>'The capacity field must be a number']);
        }
        else{
            DB::table('vehicles')->where('id',$id)->update([
                'type' => $request->input('type'),
                'license' => Carbon::createFromFormat('Y-m-d', $request->input('license')),
                'capacity' => intval($request->input('capacity')),
            ]);
            return response()->json([
                'success'=>true,
                'message'=>'The id='.$id.' vehicle is changed for type: '
                                    .$request->input('type').
                                    ', license: '.$request->input('license').
                                    ', capacity: '.$request->input('capacity')]);
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
        $vehicle = Vehicle::where('id',$id)->first();
        if($vehicle != null){
            $vehicle->delete();
            return response()->json(['message'=> 'The id='.$id.' Vehicle is deleted successfully','success'=>true]);
        }
        else{
            return response()->json(['message'=> 'The id='.$id.' Vehicle isn\'t in the database','success'=>false]);
        }
    }
}
