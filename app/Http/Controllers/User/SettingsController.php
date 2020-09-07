<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Rules\CheckSamePassword;
use App\Rules\MatchOldPassword;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\Cast;

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
                'location.latitude' => ['required', 'numeric', 'min:-90', 'max:90'],
                'location.longitude' => ['required', 'numeric', 'min:-180', 'max:180'],
            ]);


        $points = new Point($request->location['latitude'], $request->location['longitude']);


        $lat = $points->getLat();
        $long = $points->getLng();
        $location = "ST_GeomFromText('POINT($lat $long)')";

        $user->update([
            'name' => $request->name,
            'tagline' => $request->tagline,
            'formatted_address' => $request->formatted_address,
            'about' => $request->about,
            'available_to_hire' => $request->available_to_hire,
            'location' => DB::raw($location)
        ]);

        $query = "SELECT ST_AsGeoJSON(location) as location FROM `users`";
        $updated_location = DB::select($query);

        $updated_user = new UserResource($user);
        $updated_user->location = json_decode($updated_location[0]->location);

        return $updated_user;
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
