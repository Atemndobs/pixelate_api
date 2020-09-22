<?php


namespace App\Repositories\Eloquent;


use App\Http\Resources\UserResource;
use App\Models\Design;
use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;
use Grimzy\LaravelMysqlSpatial\Types\Point;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function Symfony\Component\String\s;

class UserRepository extends BaseRepository implements UserRepositoryInterface
{

    public function model()
    {
        return User::class;
    }

    public function findByEmail($email)
    {
     //   return $this->model->where(['email' => $email]);
     //   dd($this->model);
        return $this->model->where('email' , $email)->first();
    }

    public function search(Request $request)
    {
        $query = (new $this->model)->newQuery();


        // Only Designer who have designs
       if ($request->has_designs){
           $query->has('designs');
       }

       //Check for availability to hire
        if ($request->available_to_hire){
            $query->where('available_to_hire', true);
        }

        // Geographic search

        $lat = $request->latitude;
        $long = $request->longitude;
        $dist = $request->distance;
        $unit = $request->unit;



        if ($lat && $long){
            $point = new Point($lat, $long);
            $unit == 'km' ? $dist *= 1000 : $dist *= 1609.34;

            $lat = $point->getLat();
            $long = $point->getLng();
            $location = "POINT($long,$lat)";
/*
            distanceSphereExcludingSelf($geometryColumn, $geometry, $distance)
             "SQLSTATE[42000]: Syntax error or access violation: 1582 Incorrect parameter count in the call to native function
             'ST_GeomFromText'
             SQL:
             select * from `users` where st_distance_sphere(`location`, ST_GeomFromText(POINT(6.7854723 51.238924),
             0, 'axis-order=long-lat')) <= 3000 and st_distance_sphere(location, ST_GeomFromText(POINT(6.7854723 51.238924),
             0, 'axis-order=long-lat')) != 0 order by `created_at` asc)



                  $query = "SELECT ST_AsGeoJSON(location) as location FROM `users`";
                    $updated_location = DB::select($query);

                    $updated_user = new UserResource($user);
                    $updated_user->location = json_decode($updated_location[0]->location);
             select st_distance_sphere(POINT(-73.9949,40.7501), POINT( -73.9961,40.7542))

            $query = "SELECT ST_AsGeoJSON(location) as location FROM `users` where `users`.id=1";
*/
            $r_lng= "6.820870";
            $r_lat= "51.236346";
            $position = "POINT( $r_lng,$r_lat)";

            $users =$this->all();

            $results = [];
            foreach ($users as $user) {
                $user_sql = "SELECT ST_AsGeoJSON(location) as location FROM `users` where `users`.id = $user->id ";
                $user_location = DB::select($user_sql);
                $option = (json_decode($user_location[0]->location));

                $lon = $option->coordinates[1];
                $latit = $option->coordinates[0];
                $user_position = "POINT($lon,$latit)";

                $sql =  "select * from `users`
                        where  st_distance_sphere($location, $user_position) <= $dist
                        and st_distance_sphere($location, $user_position) != 0
                        and `users`.id = $user->id
                        order by `created_at` asc ";
                $results[] = \DB::select($sql);
            }

            foreach ($results as $result){
                if (!empty($result[0])){
               //     dd($results);
                    $id = $result[0]->id;
                    $local = "SELECT ST_AsGeoJSON(location) as location FROM `users` where `users`.id = $id";
                    $updated_location = DB::select($local);
                    $result[0]->location = json_decode($updated_location[0]->location);
                }
            }

            foreach ($results as $key => $result){
                if (empty($result[0])){
                    unset($results[$key]);
                }
            }

            $query = UserResource::collection($results);

           // $query->distanceSphereExcludingSelf('location', $point, $dist);
        }
        // Order the results

        if ($request->orderByLatest){

            $query->collection->sortBy(function($item){
                return $item->resource[0]->created_at;
            });
        }else{
            $query->collection->sortByDesc(function($item){
                return $item->resource[0]->created_at;
            });
        }
        return $query->collection;

    }

}
