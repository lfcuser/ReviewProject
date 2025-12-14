<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Services\Features\DeletingCoordinate;
use App\Services\Features\ItemCoordinate;
use App\Services\Features\ListCoordinate;
use App\Services\Features\NewCoordinate;
use App\Services\Features\UpdatingCoordinate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CoordinatesController extends Controller
{
    /**
     * @OA\Get(
     *   path="/api/coordinates",
     *   operationId="GetListCoordinate",
     *   tags={"Coordinate"},
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(name="page", in="query", @OA\Schema(type="integer")),
     *   @OA\Parameter(name="limit", in="query", @OA\Schema(type="integer")),
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
     *          @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/CoordinateItem"))
     *       )
     *     )
     *   ),
     *   @OA\Response(response=401, ref="#/components/responses/errorResponseUnauthorized"),
     *   @OA\Response(response=422, ref="#/components/responses/errorResponseBadRequest")
     * )
     *
     * @param Request $request
     * @param ListCoordinate $featureService
     *
     * @return JsonResponse
     */
    public function index(Request $request, ListCoordinate $featureService): JsonResponse
    {
        $data = $featureService->handle($request->all());
        return response()->success($data);
    }

    /**
     * @OA\Post(
     *   path="/api/coordinates",
     *   operationId="StoreCoordinate",
     *   tags={"Coordinate"},
     *   security={{"bearerAuth":{}}},
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"place_name","lat","lon"},
     *       @OA\Property(property="place_name", type="string"),
     *       @OA\Property(property="lat", type="number", format="float"),
     *       @OA\Property(property="lon", type="number", format="float")
     *     )
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Success",
     *     @OA\JsonContent(
     *       required={"success"},
     *       @OA\Property(property="success", type="bool", default=true),
     *       @OA\Property(property="data", type="object", ref="#/components/schemas/CoordinateItem")
     *     )
     *   ),
     *   @OA\Response(response=401, ref="#/components/responses/errorResponseUnauthorized"),
     *   @OA\Response(response=422, ref="#/components/responses/errorResponseBadRequest")
     * )
     *
     * @param Request $request
     * @param NewCoordinate $featureService
     *
     * @return JsonResponse
     */
    public function store(Request $request, NewCoordinate $featureService): JsonResponse
    {
        $dto = $featureService->handle($request->all());
        return response()->success($dto);
    }

    /**
     * @OA\Get(
     *   path="/api/coordinates/{id}",
     *   operationId="GetItemCoordinate",
     *   tags={"Coordinate"},
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(
     *     response="200",
     *     description="Success",
     *     @OA\JsonContent(
     *       required={"success"},
     *       @OA\Property(property="success", type="bool", default=true),
     *       @OA\Property(property="data", type="object", ref="#/components/schemas/CoordinateItem")
     *     )
     *   ),
     *   @OA\Response(response=401, ref="#/components/responses/errorResponseUnauthorized"),
     *   @OA\Response(response=422, ref="#/components/responses/errorResponseBadRequest")
     * )
     *
     * @param Request $request
     * @param ItemCoordinate $featureService
     *
     * @return JsonResponse
     */
    public function show(int $id, ItemCoordinate $featureService): JsonResponse
    {
        $dto = $featureService->handle(['id' => $id]);
        return response()->success($dto);
    }

    /**
     * @OA\Put(
     *   path="/api/coordinates/{id}",
     *   operationId="UpdateCoordinate",
     *   tags={"Coordinate"},
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\RequestBody(
     *     required=true,
     *     @OA\JsonContent(
     *       required={"place_name","lat","lon"},
     *       @OA\Property(property="place_name", type="string"),
     *       @OA\Property(property="lat", type="number", format="float"),
     *       @OA\Property(property="lon", type="number", format="float")
     *     )
     *   ),
     *   @OA\Response(
     *     response="200",
     *     description="Success",
     *     @OA\JsonContent(
     *       required={"success"},
     *       @OA\Property(property="success", type="bool", default=true),
     *       @OA\Property(property="data", type="object", ref="#/components/schemas/CoordinateItem")
     *     )
     *   ),
     *   @OA\Response(response=401, ref="#/components/responses/errorResponseUnauthorized"),
     *   @OA\Response(response=422, ref="#/components/responses/errorResponseBadRequest")
     * )
     *
     * @param Request $request
     * @param UpdatingCoordinate $featureService
     *
     * @return JsonResponse
     */
    public function update(Request $request, int $id, UpdatingCoordinate $featureService): JsonResponse
    {
        $request->merge(['id' => $id]);
        $dto = $featureService->handle($request->all());
        return response()->success($dto);
    }

    /**
     * @OA\Delete(
     *   path="/api/coordinates/{id}",
     *   operationId="DeleteItemCoordinate",
     *   tags={"Coordinate"},
     *   security={{"bearerAuth":{}}},
     *   @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *   @OA\Response(
     *     response="200",
     *     description="Success",
     *     @OA\JsonContent(
     *       required={"success"},
     *       @OA\Property(property="success", type="bool", default=true)
     *     )
     *   ),
     *   @OA\Response(response=401, ref="#/components/responses/errorResponseUnauthorized"),
     *   @OA\Response(response=422, ref="#/components/responses/errorResponseBadRequest")
     * )
     *
     * @param Request $request
     * @param DeletingCoordinate $featureService
     *
     * @return JsonResponse
     */
    public function delete(int $id, DeletingCoordinate $featureService): JsonResponse
    {
        $featureService->handle(['id' => $id]);
        return response()->success();
    }
}
