<?php

namespace App\Http\Controllers;

class PageController extends Controller
{
    public function home() { return view('pages.home'); }
    public function about() { return view('pages.about'); }
    public function products() { return view('pages.products'); }
    public function cattle() { return view('pages.cattle'); }
    public function pigs() { return view('pages.pigs'); }
    public function poultry() { return view('pages.poultry'); }
    public function services() { return view('pages.services'); }
    public function contact() { return view('pages.contact'); }
    public function faq() { return view('pages.faq'); }
}
