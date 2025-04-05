<?php

namespace App\Http\Controllers\VisitorLog;

use App\Actions\Response;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use App\Models\Log\VisitorLog;
use App\Http\Controllers\Controller;
use Jaybizzle\CrawlerDetect\CrawlerDetect;

class StoreController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $crawler = new CrawlerDetect;
        $agent = new Agent;

        $ip = $request->ip();
        $geo = geoip($ip);

        $isBot = $crawler->isCrawler($request->userAgent());

        $log = VisitorLog::firstOrNew(['ip' => $ip]);

        $log->fill([
            'url' => $request->url,
            'country' => $geo->country,
            'region' => $geo->state,
            'city' => $geo->city,
            'latitude' => $geo->lat,
            'longitude' => $geo->lon,
            'device' => $agent->device(),
            'platform' => $agent->platform(),
            'browser' => $agent->browser(),
            'is_bot' => $isBot,
            'referrer' => $request->referrer,
        ]);

        $log->visit_count = ($log->visit_count ?? 0) + 1;

        $log->save();

        return Response::success()->data($log)->message('Successfully');
    }
}
