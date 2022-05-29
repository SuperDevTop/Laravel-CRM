<script>
	var app = angular.module('mediaGallery', []);

	app.config(function($interpolateProvider) {
		$interpolateProvider.startSymbol('<%').endSymbol('%>');
	});

	app.controller('libraryCtrl', function($scope) {
		$scope.filterText = '';
		$scope.mediaItems = {{ json_encode(MediaItem::whereNull('deletedBy')->orderBy('createdAt', 'DESC')->get()) }};

		$scope.selectMode = false;

		$scope.selectItem = function(item) {
			$scope.currentItem = item;
		};

		$scope.deleteItem = function(item) {
			confirmDialog(
				'Are you sure?',
				'Are you sure you want to delete this media item?',
				function() {
					ajaxRequest(
						'delete_media_item',
						{
							itemId: item.id
						},
						function(data) {
							if (data.success) {
								showSuccess('Item deleted');
								delete $scope.currentItem;
								$scope.mediaItems.splice($scope.mediaItems.indexOf(item), 1);
								$scope.$apply();
							} else {
								showError('Could not delete item. Please try again.');
							}
						}
					);
				}
			);
		};

		$scope.saveItem = function() {
			ajaxRequest(
				'update_media_item',
				{
					item: $scope.currentItem
				},
				function(data) {
					if (data.success)
						showSuccess('Details saved');
					else
						showError('Could not save item. Please try again.');
				}
			);
		};

		$scope.inNameOrDesc = function(item) {
			return (item.name.toLowerCase().indexOf($scope.filterText.toLowerCase()) > -1 || item.description.toLowerCase().indexOf($scope.filterText.toLowerCase()) > -1);

		}

		$scope.dragCounter = 0;

		$('#media_library').on('dragstart', function() {});

		$('#media_library').on('dragover', function(event) {
			event.preventDefault();
		});

		$('#media_library').on('dragenter', function(event) {
			var fileTypes = event.originalEvent.dataTransfer.types;
			var containsFiles = false;
			for(var i=0; i<fileTypes.length; i++) {
				if (fileTypes[i] == 'Files')
					containsFiles = true;
			}

			console.log(event.originalEvent.dataTransfer.mozSourceNode);

			if (!containsFiles || event.originalEvent.dataTransfer.mozSourceNode != null)
				return;

			$scope.dragCounter++;
			$('.drop-zone').addClass('active');
			event.preventDefault();
		});

		$('#media_library').on('dragleave', function(event) {
			$scope.dragCounter--;
			if ($scope.dragCounter === 0) {
				$('.drop-zone').removeClass('active');
			}
		})

		$('#library_wrapper i.close-btn').on('click', function(event) {
			$('#mask').fadeOut(200);

			$('#media_library').hide();
			$('#media_library').html("<img class='preloader' src='/img/preloader_2.gif'>");

			$('#sidebar').removeClass('blurred');
			$('#top_bar').removeClass('blurred');
			$('#main_content').removeClass('blurred');
		});

		$('.drop-zone').on('drop', function(event) {
			$('.drop-zone').removeClass('active');
			event.preventDefault();

			var files = event.originalEvent.dataTransfer.files;

			// Now, for each of the dropped files, we create a FormData object and send the file to the server...
			for (var i=0; i<files.length; i++) {
				(function() {
					var fd = new FormData;
					fd.append('file', files[i]);

					// Make a unique ID for the position in the array
					var uid = "";
				    var possible = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
				    for(var u=0; u<10; u++)
				        uid += possible.charAt(Math.floor(Math.random() * possible.length));

					// Add item
					$scope.mediaItems.unshift({
						id: -1,
						'uid': uid,
						name: 'uploading',
						description: 'uploading',
						uploading: true,
						progress: 0
					});
					$scope.$apply();

					var ajax = $.ajax({
						xhr: function() {
							var xhrobj = $.ajaxSettings.xhr();
							if (xhrobj.upload) {
			                    xhrobj.upload.addEventListener('progress', function(event) {
			                        var percent = 0;
			                        var position = event.loaded || event.position;
			                        var total = event.total;
			                        if (event.lengthComputable) {
			                            percent = Math.ceil(position / total * 100);
			                        }
			                        //Set progress
			                        for (var u=0; u<$scope.mediaItems.length; u++) {
			                        	var curItem = $scope.mediaItems[u];

			                        	if (curItem.uid == uid) {
			                        		console.log('Found id: ' + curItem.uid);
			                        		curItem.progress = percent;
				                        	$scope.$apply();
			                        	}
			                        }
			                    }, false);
			                }
				            return xhrobj;
						},
						url: 'ajax/media_library_upload',
						dataType: 'JSON',
						type: 'POST',
						contentType: false,
						processData: false,
						cache: false,
						data: fd,
						success: function(data) {
							for (var u=0; u<$scope.mediaItems.length; u++) {
	                        	var curItem = $scope.mediaItems[u];

	                        	if (curItem.uid == uid) {
	                        		delete curItem.uploading;
									delete curItem.progress;
									for (var k in data.item) {
										curItem[k] = data.item[k];
									}
									$scope.$apply();
	                        	}
	                        }
						}
					});
				})();
			}
		});
	});

	angular.bootstrap($('#library_wrapper'), ['mediaGallery']);
</script>
<div id='library_wrapper' ng-controller='libraryCtrl'>
	<div class="drop-zone">
		<h1><i class="fa fa-upload"></i></h1>
		<h2>Drop media here</h2>
	</div>
	<i class="fa fa-remove close-btn"></i>
	<h1>Media Library</h1>
	<div class="media-grid">
		<input type="text" placeholder='Search...' ng-model='filterText'>
		<br clear='both'>

		<div class="no-items" ng-show='mediaItems.length == 0'><i class="fa fa-frown-o"></i><br>You don't have any items in the library yet.<br>Drag some items into this window to start uploading!</div>
		<div class="media-item" ng-repeat='item in mediaItems | filter:inNameOrDesc' ng-click='selectItem(item)' ng-class='{ "active": currentItem == item }'>
			<i class="fa fa-cloud-upload uploading-icon" ng-show='item.uploading'></i>
			<img class='thumbnail' ng-src='<% (item.hasThumbnail) ? item.thumbUrl : item.url %>'>
			<div class="progressbar" ng-show='item.uploading'><div class="progressbar-inner" ng-style='{ "width": item.progress + "%" }'></div></div>
		</div>
	</div>
	<div ng-hide='currentItem' class='fr' style='margin-top: 200px;'>Please select an item to access its properties...</div>
	
	<button class="btn btn-green" style='width: 270px; margin-left: 15px; margin-top: 200px;' ng-show='currentItem && selectMode' id='select-btn'><i class="fa fa-check"></i> Use image</button>

	<div class="media-details" ng-show='currentItem && !selectMode'>
		<h2><% currentItem.name %></h2>
		<img ng-src='<% currentItem.url %>'>

		<input type='text' ng-model='currentItem.name' placeholder='Name'>
		<textarea ng-model='currentItem.description' placeholder='Description'></textarea>
		<button class="btn btn-green fr" ng-click='saveItem();'><i class="fa fa-save"></i> Save</button>
		<a href='#' style='color: red; display: block; margin-top: 10px; text-decoration: none;' onclick='return false;' ng-click='deleteItem(currentItem)'>Delete image</a>

	</div>
</div>