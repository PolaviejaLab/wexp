(function() {
	
	// Create new application
	var app = angular.module('experiment', []);

	// Change symbols not to interfere with twig
	app.config(function($interpolateProvider){
		$interpolateProvider.startSymbol('{[{').endSymbol('}]}');
	});

}());