(function() {

	var app = angular.module('experiment');
	
	
	/**
	 * Return items in n that have changed
	 * with respect to o.
	 */
	function diff(o, n)
	{
		var i, 
			res = {},
		    keys = Object.keys(n).sort();
	
		for(i = 0; i < keys.length; i++) {
			if(o[keys[i]] != n[keys[i]]) {
				res[keys[i]] = n[keys[i]];
			};		
		};

		return res;
	};
	
	
	app.controller('ExperimentController', ['$scope', '$http', function($scope, $http) {
		
		// Load responses from parent javascript
		$scope.responses = responses;
		
		var currentScreen = 2;
		
		
		// Notify server of updates
		$scope.$watch(function() { return $scope.responses; }, function(newValue, oldValue) {	
			var d = diff(oldValue, newValue);
			
			console.log(d);
			
			$http.post(responseSink, d);
		}, true);
		
		
		$scope.isVisible = function(id, name) {
			return (id == currentScreen) || (name == currentScreen);
		}
		
		
		$scope.changeScreen = function(name) {
			currentScreen = name;
		}
		
	
	}]);

}());