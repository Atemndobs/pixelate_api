<?php


namespace App\Models;

/**
 * @OA\Schema(
 *      @OA\Property(property="created_at_human", type="string", example="2 days ago", description="Initial creation, Human readable", readOnly="true"),
 *      @OA\Property(property="created_at", type="string", format="date-time", description="Initial creation timestamp", readOnly="true"),
 *      @OA\Property(property="updated_at_human", type="string", example="2 days ago", description="Human readable update time", readOnly="true"),
 *      @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp", readOnly="true"),
 *      @OA\Property(property="deleted_at", type="string", format="date-time", description="Soft delete timestamp", readOnly="true"),
 * )
 * Class BaseModel
 *
 * @package App\Models
 */
abstract class BaseModel extends \Illuminate\Database\Eloquent\Model
{

}
