<?php

namespace App\Http\Controllers\Api;

use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Resources\PlanResource;
use App\Http\Controllers\Controller;
use App\Http\Resources\PlanCollection;
use App\Http\Requests\PlanStoreRequest;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\PlanUpdateRequest;

/**
 * @OA\Info(title="My First API", version="0.1")
 * 
 * @OA\Schema(
 *     schema="PlanRequest",
 *     type="object",
 *     required={"name", "price", "duration", "slots"},
 *     @OA\Property(property="image", type="string", format="binary", description="Optional image file, max size 1MB"),
 *     @OA\Property(property="name", type="string", description="The name of the plan"),
 *     @OA\Property(property="price", type="number", description="The price of the plan"),
 *     @OA\Property(property="duration", type="integer", description="The duration of the plan in days"),
 *     @OA\Property(property="slots", type="integer", description="The number of slots available for the plan")
 * )
 * 
 * @OA\Schema(
 *     schema="PlanResource",
 *     type="object",
 *     @OA\Property(property="id", type="integer"),
 *     @OA\Property(property="name", type="string"),
 *     @OA\Property(property="price", type="number"),
 *     @OA\Property(property="duration", type="integer"),
 *     @OA\Property(property="slots", type="integer"),
 *     @OA\Property(property="image", type="string", description="URL of the plan image"),
 *     @OA\Property(property="created_at", type="string", format="date-time"),
 *     @OA\Property(property="updated_at", type="string", format="date-time")
 * )
 * 
 * @OA\Schema(
 *     schema="PlanCollection",
 *     type="object",
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(ref="#/components/schemas/PlanResource")
 *     ),
 *     @OA\Property(property="links", type="object", 
 *         @OA\Property(property="first", type="string"),
 *         @OA\Property(property="last", type="string"),
 *         @OA\Property(property="prev", type="string", nullable=true),
 *         @OA\Property(property="next", type="string", nullable=true)
 *     ),
 *     @OA\Property(property="meta", type="object",
 *         @OA\Property(property="current_page", type="integer"),
 *         @OA\Property(property="from", type="integer"),
 *         @OA\Property(property="last_page", type="integer"),
 *         @OA\Property(property="path", type="string"),
 *         @OA\Property(property="per_page", type="integer"),
 *         @OA\Property(property="to", type="integer"),
 *         @OA\Property(property="total", type="integer")
 *     )
 * )
 * 
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 * )
 */

class PlanController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/plans",
     *     summary="Get list of plans",
     *     description="Retrieve a paginated list of plans.",
     *     operationId="getPlans",
     *     tags={"Plans"},
     *     @OA\Parameter(
     *         name="search",
     *         in="query",
     *         description="Search term to filter plans by name",
     *         required=false,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Successful response",
     *         @OA\JsonContent(ref="#/components/schemas/PlanCollection")
     *     )
     * )
     */
    public function index(Request $request): PlanCollection
    {
        $search = $request->get('search', '');

        $plans = $this->getSearchQuery($search)
            ->latest()
            ->paginate();

        return new PlanCollection($plans);
    }

    /**
     * @OA\Post(
     *     path="/api/plans",
     *     summary="Create a new plan",
     *     description="Store a new plan in the database.",
     *     operationId="storePlan",
     *     tags={"Plans"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/PlanRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Plan created successfully",
     *         @OA\JsonContent(ref="#/components/schemas/PlanResource")
     *     )
     * )
     */
    public function store(PlanStoreRequest $request): PlanResource
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('public');
        }

        $plan = Plan::create($validated);

        return new PlanResource($plan);
    }

    /**
     * @OA\Get(
     *     path="/api/plans/{plan}",
     *     summary="Get a specific plan",
     *     description="Retrieve details of a specific plan by its ID.",
     *     operationId="getPlan",
     *     tags={"Plans"},
     *     @OA\Parameter(
     *         name="plan",
     *         in="path",
     *         description="ID of the plan to retrieve",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Plan retrieved successfully",
     *         @OA\JsonContent(ref="#/components/schemas/PlanResource")
     *     )
     * )
     */
    public function show(Request $request, Plan $plan): PlanResource
    {
        return new PlanResource($plan);
    }

    /**
     * @OA\Put(
     *     path="/api/plans/{plan}",
     *     summary="Update a plan",
     *     description="Update the details of an existing plan.",
     *     operationId="updatePlan",
     *     tags={"Plans"},
     *     @OA\Parameter(
     *         name="plan",
     *         in="path",
     *         description="ID of the plan to update",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/PlanRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Plan updated successfully",
     *         @OA\JsonContent(ref="#/components/schemas/PlanResource")
     *     )
     * )
     */
    public function update(PlanUpdateRequest $request, Plan $plan): PlanResource
    {
        $validated = $request->validated();

        if ($request->hasFile('image')) {
            if ($plan->image) {
                Storage::delete($plan->image);
            }

            $validated['image'] = $request->file('image')->store('public');
        }

        $plan->update($validated);

        return new PlanResource($plan);
    }

    /**
     * @OA\Delete(
     *     path="/api/plans/{plan}",
     *     summary="Delete a plan",
     *     description="Remove a plan from the database.",
     *     operationId="deletePlan",
     *     tags={"Plans"},
     *     @OA\Parameter(
     *         name="plan",
     *         in="path",
     *         description="ID of the plan to delete",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(
     *         response=204,
     *         description="Plan deleted successfully"
     *     )
     * )
     */
    public function destroy(Request $request, Plan $plan): Response
    {
        if ($plan->image) {
            Storage::delete($plan->image);
        }

        $plan->delete();

        return response()->noContent();
    }

    /**
     * Helper method to build the search query.
     */
    public function getSearchQuery(string $search)
    {
        return Plan::query()->where('name', 'like', "%{$search}%");
    }
}
