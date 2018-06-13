<?php

namespace Belt\Spot\Http\Controllers\Web;

use Belt\Content\Http\Controllers\Compiler;
use Belt\Core\Http\Controllers\BaseController;
use Belt\Spot\Place;

/**
 * Class PlacesController
 * @package Belt\Spot\Http\Controllers\Web
 */
class PlacesController extends BaseController
{

    use Compiler;

    /**
     * ApiController constructor.
     */
    public function __construct()
    {
        $this->middleware('web');
    }

    /**
     * Display the specified resource.
     *
     * @param  Place $deal
     *
     * @return \Illuminate\View\View
     */
    public function show(Place $deal)
    {
        if (!$deal->is_active) {
            try {
                $this->authorize('update', $deal);
            } catch (\Exception $e) {
                abort(404);
            }
        }

        $compiled = $this->compile($deal);

        $owner = $deal;

        $view = $deal->getTemplateConfig('extends', 'belt-spot::deals.web.show');

        return view($view, compact('owner', 'deal', 'compiled'));
    }

}