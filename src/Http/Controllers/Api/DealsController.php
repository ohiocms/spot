<?php

namespace Belt\Spot\Http\Controllers\Api;

use Belt;
use Belt\Core\Http\Controllers\ApiController;
use Belt\Spot\Http\Requests;
use Belt\Spot\Deal;
use Illuminate\Http\Request;

class DealsController extends ApiController
{

    /**
     * @var Deal
     */
    public $deals;

    /**
     * ApiController constructor.
     * @param Deal $deal
     */
    public function __construct(Deal $deal)
    {
        $this->deals = $deal;
    }

    public function get($id)
    {
        return $this->deals->find($id) ?: $this->abort(404);
    }

    /**
     * Display a listing of the resource.
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $this->authorize(['view', 'create', 'update', 'delete'], Deal::class);

        $request = Requests\PaginateDeals::extend($request);

        $paginator = $this->paginator($this->deals->query(), $request);

        return response()->json($paginator->toArray());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Requests\StoreDeal $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\StoreDeal $request)
    {
        $this->authorize('create', Deal::class);

        if ($source = $request->get('source')) {
            return response()->json($this->deals->copy($source), 201);
        }

        $input = $request->all();

        $deal = $this->deals->create([
            'name' => $input['name'],
        ]);

        $this->set($deal, $input, [
            'team_id',
            'attachment_id',
            'is_active',
            'is_searchable',
            'priority',
            'status',
            'slug',
            'intro',
            'body',
            'hours',
            'url',
            'email',
            'phone',
            'phone_tollfree',
            'meta_title',
            'meta_description',
            'meta_keywords',
            'starts_at',
            'ends_at',
        ]);

        $deal->save();

        $this->itemEvent('created', $deal);

        return response()->json($deal, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $deal = $this->get($id);

        $this->authorize(['view', 'create', 'update', 'delete'], $deal);

        return response()->json($deal);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Requests\UpdateDeal $request
     * @param  string $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\UpdateDeal $request, $id)
    {
        $deal = $this->get($id);

        $this->authorize('update', $deal);

        $input = $request->all();

        $this->set($deal, $input, [
            'team_id',
            'attachment_id',
            'is_active',
            'is_searchable',
            'priority',
            'status',
            'name',
            'slug',
            'intro',
            'body',
            'hours',
            'url',
            'email',
            'phone',
            'phone_tollfree',
            'meta_title',
            'meta_description',
            'meta_keywords',
            'starts_at',
            'ends_at',
        ]);

        $deal->save();

        $this->itemEvent('updated', $deal);

        return response()->json($deal);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $deal = $this->get($id);

        $this->authorize('delete', $deal);

        $this->itemEvent('deleted', $deal);

        $deal->delete();

        return response()->json(null, 204);
    }
}
