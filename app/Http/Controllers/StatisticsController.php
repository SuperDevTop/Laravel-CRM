<?php

namespace App\Http\Controllers;

class StatisticsController extends BaseController {
	protected $layout = 'master';
	public function getOverview() {
		// $this->layout->nav_main = 'Statistics';
		// $this->layout->nav_sub = 'Week overview';
		// $this->layout->content = view('statistics.overview');
		return view('statistics.overview', 
			[
				'nav_main' => 'Statistics',
				'nav_sub' => 'Week overview'
			]);
	}

}