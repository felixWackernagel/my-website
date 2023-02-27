<?php

namespace App\Http\Controllers\API;

use App\Models\Location;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Intervention\Image\Facades\Image;

class LocationController extends Controller
{
    public function getAll() {
        $locations = Location::select( 'id', 'name' )->orderBy( 'name', 'ASC' )->get();
        return response()->json( [
            "locations" => $locations
        ], 200);
    }

    public function edit_location()
    {
        return Location::orderBy( 'id', 'desc' )->first();
    }

    public function update_location( Request $request, $id )
    {
        $location = Location::find( $id );
        $this->validate( $request, [
            'name' => 'required'
        ] );
        if( $location->photo != $request->photo )
        {
            $strpos = strpos( $request->photo, ';' );
            $sub = substr( $request->photo, 0, $strpos );
            $ex = explode( '/', $sub )[1];
            $name = time() . '.' . $ex;
            $img = Image::make( $request->photo )->resize( 700, 500 );
            $upload_path = public_path() . '/img/upload/';
            $image = $upload_path . $location->photo; // existing image
            $img->save( $upload_path . $name );
            if( file_exists( $image ) ) {
                @unlink( $image );
            } else {
                $name = $location->photo;
            }
        }

        $location->name = $request->name;
        $location->website = $request->website;
        $location->phone = $request->phone;
        $location->address = $request->address;
        $location->photo = $name;
        $location->save();
    }
}
