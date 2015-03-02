
(function() {
	var app = angular.module('experiment', [ ]);
	var root = 'http://ccu.lisbon.ivarclemens.nl/~ivar/2015-01-12-symfony/';
	
	app.controller('ScreensCtrl', ['$http', function($http) {
		var screens = this.screens;
		
		screens = {};
		
		$http.get(root + 'app_dev.php/admin/screens')
			.success(function(data) {
				this.screens = data;
			});
	}]);
	
	app.directive('screen', function() {
		return {
			restrict: 'E',
			templateUrl: root + 'template/screen.html',
			
		};
	});

	
	
})();