<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use function PHPUnit\Framework\countOf;

class DraftController extends Controller
{
    // for draft purposes

    public function home() {
        return view('draft.landing.home');
    }

    // under what's hot
    public function wallpapers() {
        return view('draft.landing.wallpapers');
    }

    public function articles() {
        return view('draft.landing.articles');
    }

    public function articleDetails($name) {
        return view('draft.subpages.article', compact('name'));
    }

    public function podcasts() {
        return view('draft.landing.podcasts');
    }

    public function podcastDetails($name) {
        return view('draft.subpages.podcast', compact('name'));
    }
    // end

    public function shows() {
        return view('draft.landing.shows');
    }

    public function showDetails($name) {
        return view('draft.subpages.show', compact('name'));
    }

    // under jocks
    public function jocks() {
        return view('draft.landing.jocks');
    }

    public function jockDetails($name) {
        return view('draft.subpages.jock', compact('name'));
    }

    public function studentJocks() {
        return view('draft.landing.radio1');
    }
    // end

    // under charts
    public function charts() {
        return view('draft.landing.charts');
    }

    public function allTimeHits() {
        return view('draft.landing.chartHits');
    }

    public function daily() {
        return view('draft.landing.dailycharts');
    }

    public function voting() {
        return view('draft.landing.voting');
    }
    // end

    public function indieground() {
        return view('draft.landing.indiegrounds');
    }

    // under others
    public function gimikboard() {
        return view('draft.landing.gimikboard');
    }

    public function gimikboardDetails($name) {
        return view('draft.subpages.gimikboard', compact('name'));
    }

    public function scholars() {
        return view('draft.landing.scholars');
    }

    public function about() {
        return view('draft.landing.about');
    }

    public function contact() {
        return view('draft.landing.contact');
    }
    // end


    public function live() {
        return view('draft.landing.live');
    }
}
