<?php

namespace App\Http\Controllers;

use App\PortalCms;

class PortalConsumerismController extends Controller
{
    public function __invoke()
    {
        $sliderImages = PortalCms::GetPortalContent(2);
        $section3Images = PortalCms::GetPortalContent(4);
        $section4Images = ''; //PortalCms::GetPortalContent(5);
        return view('welcome_kepenggunaan', compact('sliderImages', 'section3Images', 'section4Images'));
    }
}
