<?php

namespace App\Services;

use App\Traits\ConsumesExternalService;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class TimelineService
{
    use ConsumesExternalService;

    public $baseUri;
    public $api_key;

    public function __construct()
    {
        $env           = "pm";
        $this->baseUri = config("gateway_services.$env.base_uri");
        $this->api_key = config("gateway_services.$env.api_key");
    }


    public function show($request)
    {
        $user_id = auth()->user()->lead_id;
        try {

            $tickets = json_decode($this->performRequest('get', "tickets/getTickets/" . $user_id));

            if (isset($tickets->data[0]) && isset($tickets->data[0]->ticket)) {
                return json_decode($this->performRequest('get', "timeline/" . $tickets->data[0]->ticket->id));
            }
            throw new BadRequestHttpException('There is not project exists', null, 404);

        } catch (\Exception $e) {
            throw $e;
            return Response::errorResponse('not found', null, 404);
        }


    }

}
