<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Rules\CheckSamePassword;
use App\Rules\MatchOldPassword;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    public function updateProfile(Request $request)
    {
        $user = auth()->user();




            $this->validate($request, [
                'name' => ['required'],
                'tagline' => ['required'],
                'formatted_address' => ['required'],
                'about' => ['required', 'string', 'min:20'],
                'available_to_hire' => ['required'],
                'location.latitude' => ['required', 'int', 'min:-90', 'max:90'],
                'location.longitude' => ['required', 'int', 'min:-180', 'max:180'],
            ]);


        $location = new Point($request->location['latitude'], $request->location['longitude']);

        $lat = $location->getLat();
        $long = $location->getLng();

        // CREATE SPATIAL REFERENCE SYSTEM 104326 NAME 'WGS 84 N-E' DEFINITION 'GEOGCS["WGS 84",DATUM["World Geodetic System 1984",SPHEROID["WGS 84",6378137,298.257223563,AUTHORITY["EPSG","7030"]],AUTHORITY["EPSG","6326"]],PRIMEM["Greenwich",0,AUTHORITY["EPSG","8901"]],UNIT["degree",0.017453292519943278,AUTHORITY["EPSG","9122"

       # $query = "update `users` set `location` = ST_GeomFromText('POINT($lat $long)') where `id` = $user->id";
        $query = "update `users` set `location` = ST_GeomFromText('POINT($lat $long)') where `id` = $user->id";



        DB::statement(
            $query
        );

        $data = DB::select('select * from users');



        $user->update([
            'name' => $request->name,
            'tagline' => $request->tagline,
            'formatted_address' => $request->formatted_address,
            'about' => $request->about,
            'available_to_hire' => $request->available_to_hire,
            'location'=>$location
        ]);

        return new UserResource($user);
    }

    public function updatePassword(Request $request)
    {
        //get current password, new password and password confirmation

        $this->validate($request, [
            'current_password' => ['required', new MatchOldPassword()],
            'password' =>['required', 'confirmed', 'min:6', new CheckSamePassword()]
        ]);

        $request->user()->update([
            'password' => bcrypt($request->password)
        ]);

        return response()->json(['message' => 'Password Updated'], 200);
    }
}
