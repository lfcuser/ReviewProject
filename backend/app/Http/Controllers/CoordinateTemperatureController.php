<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Features\ListCoordinateTemperature;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CoordinateTemperatureController extends Controller
{
    /**
     * @OA\Get(
     *   path="/api/coordinates_temperature",
     *   operationId="GetListCoordinateTemperature",
     *   tags={"CoordinateTemperature"},
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(name="page", in="query", @OA\Schema(type="integer")),
     *   @OA\Parameter(name="limit", in="query", @OA\Schema(type="integer")),
     *   @OA\Parameter(name="date", in="query", @OA\Schema(type="string", format="Y-m-d H:i:s")),
     *   @OA\Parameter(name="lat", in="query", @OA\Schema(type="number", format="float")),
     *   @OA\Parameter(name="lon", in="query", @OA\Schema(type="number", format="float")),
     *   @OA\Parameter(name="source", in="query", @OA\Schema(type="string")),
     *   @OA\Response(
     *     response="200",
     *     description="Success",
     *     @OA\JsonContent(
     *       required={"success"},
     *       @OA\Property(property="success", type="bool", default=true),
     *       @OA\Property(property="data", type="object",
     *          required={"current_page","last_page","per_page","total","data"},
     *          @OA\Property(property="current_page", type="integer"),
     *          @OA\Property(property="last_page", type="integer"),
     *          @OA\Property(property="per_page", type="integer"),
     *          @OA\Property(property="total", type="integer"),
     *          @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/CoordinateTemperatureItem"))
     *       )
     *     )
     *   ),
     *   @OA\Response(response=401, ref="#/components/responses/errorResponseUnauthorized"),
     *   @OA\Response(response=422, ref="#/components/responses/errorResponseBadRequest")
     * )
     *
     * @param Request $request
     * @param ListCoordinateTemperature $featureService
     *
     * @return JsonResponse
     */
    public function index(Request $request, ListCoordinateTemperature $featureService): JsonResponse
    {
        $data = $featureService->handle($request->all());
        return response()->success($data);
    }
}
