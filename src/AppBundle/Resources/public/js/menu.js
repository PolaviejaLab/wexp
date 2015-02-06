(function() {

	var app = angular.module('experiment');
	
	app.controller('MenuController', ['$scope', function($scope) {
		var collapsed = false;
		
		$scope.toggleCollapsed = function()
		{
			collapsed = !collapsed;
		};
		
		$scope.isCollapsed = function()
		{
			return collapsed;
		};
		
		
		var dropdown = [];
		
		$scope.toggleDropdown = function(name)
		{
			dropdown[name] = !dropdown[name];
			
		};
		
		$scope.hasDroppedDown = function(name)
		{
			return dropdown[name];
		};
	}]);

}());