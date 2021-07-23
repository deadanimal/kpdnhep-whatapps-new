<?php

namespace App\Http\Controllers;

use App\PortalCms;

class PortalIntegrityController extends Controller
{
    public function __invoke()
    {
        $sliderImages = PortalCms::GetPortalContent(102);
        $section3Images = PortalCms::GetPortalContent(104);
        $section4Images = PortalCms::GetPortalContent(105);
        return view('welcome_integriti', compact('sliderImages', 'section3Images', 'section4Images'));
    }
}
