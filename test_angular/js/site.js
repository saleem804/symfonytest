var myApp = angular.module('myApp', []);

myApp.directive('fileModel', ['$parse', function ($parse) {
    return {
        restrict: 'A',
        link: function(scope, element, attrs) {
            var model = $parse(attrs.fileModel);
            var modelSetter = model.assign;
            element.bind('change', function(){
				scope.$apply(function(){
					scope.showimg = false;
					modelSetter(scope, element[0].files[0]);
                });
            });
        }
    };
}]);

myApp.service('fileUpload', ['$http', function ($http) {
    this.uploadFileToUrl = function(file, uploadUrl,data,id,$scope){
        var fd = new FormData();
        fd.append('file', file);
		fd.append('label', data);
		fd.append('id', id);
        $http.post(uploadUrl, fd, {
            transformRequest: angular.identity,
            headers: {'Content-Type': undefined}
        })
        .success(function(response){
			$scope.showloader = false;
			$scope.showmsg = true;
			if(response.status == "success"){
				
				$scope.ResponseMessage = response.SuccessMessage;
				$scope.Images = response.data;
				
			}
				
			else if(response.status == "error")
				$scope.ResponseMessage = response.ErrorMessage;
			
        })
        .error(function(){
			console.log('in error' );
        });
    }
}]);




myApp.controller('fetchImagesCtrl', function($scope, $http,$window,$location,$anchorScroll, fileUpload) {
	
	$scope.apibaseUrl = "http://localhost/symfonytest/test_api/web/";
	$scope.apiUrl = $scope.apibaseUrl + "app_dev.php/";
	$scope.image_dir = $scope.apibaseUrl +"upload/images/";
	$scope.imageId = 0;
	$scope.showimg = false;
	$scope.showloader = false;
	$scope.showmsg = false;
	$scope.savebutton ="Add";
    $scope.uploadFile = function(){
		$scope.showimg = false;
		$scope.showloader = true;
        var uploadUrl = $scope.apiUrl + "image/add";
		
		if($scope.label != undefined){
			fileUpload.uploadFileToUrl($scope.myFile, uploadUrl,$scope.label,$scope.imageId,$scope);

		} else{
			$scope.showloader = false;
			$scope.showmsg = true;
			$scope.ResponseMessage = "Label is required Field";
		}
				
    };
	
	$scope.fetchImages = function(){
		$http.get($scope.apiUrl+ 'images/fetch' )
		.success(function(response) {$scope.Images = response.data;});
		

	};
	$scope.fetchImages();
		
	$scope.edit = function(id,label,filename){
        $scope.imageId = id;
		$scope.label = label;
		$scope.FileName = filename;
		$scope.showimg = true;
        $scope.showmsg = false;
		$scope.savebutton ="Update";
		$location.hash('top');
		$anchorScroll();
	};
	$scope.delete = function(id){
		var deleteUser = $window.confirm('Are you sure you want to delete?');
		if (deleteUser) {
			$scope.imageId = 0;
			$scope.label = '';
			$scope.FileName = '';
			$scope.showimg = false;
			$scope.showmsg = false;
			$scope.savebutton ="Add";
			$scope.showloader = true;
			var fd = new FormData();
			fd.append('id', id);
			var url = $scope.apiUrl+ 'image/delete';
			$http.post(url, fd, {
				transformRequest: angular.identity,
				headers: {'Content-Type': undefined}
			})
			.success(function(response){
				if(response.status == "success")
					$scope.Images = response.data;
					$scope.showloader = false;
				
			})
			.error(function(){
				console.log('in error' );
			});
		}

	};
	$scope.addNewImg = function(){
		$scope.imageId = 0;
		$scope.label = '';
		$scope.FileName = '';
		$scope.showimg = false;
		$scope.showmsg = false;
		$scope.savebutton ="Add";
	};
});
