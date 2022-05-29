<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<title>Electronbox Portal - New customer</title>
		<link href='http://fonts.googleapis.com/css?family=PT+Sans:400,700' rel='stylesheet' type='text/css'>
		<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
		<meta name="apple-mobile-web-app-capable" content="yes">
		<meta name="viewport" content="width=device-width, initial-scale=0.7, user-scalable=no;user-scalable=0;">
		{{ HTML::style('css/normalize.css') }}
		{{ HTML::style('css/tablet/newcustomer.css') }}
		{{ HTML::style('css/tablet/wizard.css') }}
		{{ HTML::script('js/jquery.js') }}
		{{ HTML::script('js/angularjs.min.js') }}
		{{ HTML::script('js/jSignature.min.js') }}
		<script>
			var app = angular.module('app', []);

			app.config(function($interpolateProvider) {
				$interpolateProvider.startSymbol('<%').endSymbol('%>');
			});

			app.controller('WizardCtrl', function($scope) {
				$scope.step = 1;

				$scope.hasCompany = false;

				$scope.details = {
					title: '',
					name: '',
					companyName: '',
					cifnif: '',
					address: '',
					city: '',
					region: '',
					postcode: '',
					phone: '',
					mobile: '',
					email: ''
				};

				$scope.saveForm = function() {

					// Check if terms and conditions are accepted
					if ($('input[name="acceptTandC"]:checked').val() != 1)
						alert('You have to accept the terms and conditions in order to continue.');

					// Check if no signature is entered
					if ($('#signature').jSignature("isModified") == false)
						alert('You have to set your signature in order to continue!');

					$.ajax({
						url: '{{ Request::root() }}/tablet/create_customer',
						method: 'POST',
						dataType: 'JSON',
						data: {
							details: $scope.details,
							signature: encodeURIComponent($('#signature').jSignature("getData"))
						},
						success: function(data) {
							if (data.success) {
								location.assign('/tablet/success');
							}
						}
					});
				};


				$scope.increaseStep = function() {
					$scope.step++;
					$scope.$apply();

					switch($scope.step) {
						case 4:
						$('#cifnif').focus();
						break;
						case 5:
						$('#address').focus();
						break;
						case 6:
						$('#city').focus();
						break;
						case 7:
						$('#region').focus();
						break;
						case 8:
						$('#postcode').focus();
						break;
						case 9:
						$('#phone').focus();
						break;
						case 10:
						$('#mobile').focus();
						break;
						case 11:
						$('#email').focus();
						break;
						case 12:
						$('#adtype').trigger('click');
						break;
					}
				};

				$scope.decreaseStep = function() {
					$scope.step--;
				};

				$scope.setTitle = function(event) {
					$scope.details.title = $(event.target).html();
					$('button.radio').removeClass('selected');
					$(event.target).addClass('selected');
					$scope.increaseStep();
					$scope.$apply();
					$('#contactName').focus();
				};

				$scope.enterSignaturePage = function() {
					$scope.step++;
					$scope.$apply();
					setTimeout(function() {
						$('#signature').jSignature();
					}, 50);	
				};

				$scope.enableHasCompany = function() {
					$scope.hasCompany = true;
					setTimeout(function() {
						$('#companyName').focus();
					}, 10);
				};
			});
		</script>
	</head>
	<body ng-app='app' ng-controller='WizardCtrl'>
		<div id='wizard_container'>
			<div data-step='1' ng-show='step == 1'>
				<h2>Please select your title?</h2>
				<center>
					<button type='button' class="radio" ng-click='setTitle($event)' data-value='Mr'>Mr</button>
					<button type='button' class="radio" ng-click='setTitle($event)' data-value='Mrs'>Mrs</button>
					<button type='button' class="radio" ng-click='setTitle($event)' data-value='Mr & Mrs'>Mr & Mrs</button>
					<button type='button' class="radio" ng-click='setTitle($event)' data-value='Miss'>Miss</button>
					<button type='button' class="radio" ng-click='setTitle($event)' data-value='Dr'>Dr</button>
				</center>
			</div>

			<div data-step='2' ng-show='step == 2'>
				<h2>Great, and your name?</h2>
				<input class='tac' id='contactName' ng-model='details.name' placeholder='Please type your first and lastname' type='text'>
				<button type='button' class='nextstep' ng-click='increaseStep()'><i class="fa fa-check"></i> Next</button>
				<button type='button' class='previousstep' ng-click='decreaseStep()'><i class="fa fa-arrow-left"></i> Back</button>
			</div>

			<div data-step='3' ng-show='step == 3'>
				<h2 ng-hide='hasCompany'>Do you have a company?</h2>
				<h2 ng-show='hasCompany'>What's your company's name?</h2>
				<center>
					<button type='button' ng-hide='hasCompany' class="radio green" ng-click='enableHasCompany()'><i class="fa fa-check"></i> Yes</button>
					<button type='button' ng-hide='hasCompany' class="radio" ng-click='hasCompany = false; increaseStep()'><i class="fa fa-remove"></i> No</button>
					<br>
					<input class='tac' type='text' id='companyName' ng-model='details.companyName' ng-show='hasCompany'>
					<button type='button' class='nextstep' ng-show='hasCompany' ng-click='increaseStep()'><i class="fa fa-check"></i> Next</button>
					<button type='button' class='previousstep' ng-click='decreaseStep()'><i class="fa fa-arrow-left"></i> Back</button>
				</center>
			</div>

			<div data-step='4' ng-show='step == 4'>
				<h2>What is the VAT registration number?</h2>
				<input class='tac' id='cifnif' ng-model='details.cifnif' placeholder='Your CIF / NIF / NIE' type='text'>
				<button type='button' class='nextstep' ng-click='increaseStep()'><i class="fa fa-check"></i> Next (or skip)</button>
				<button type='button' class='previousstep' ng-click='decreaseStep()'><i class="fa fa-arrow-left"></i> Back</button>
			</div>

			<div data-step='5' ng-show='step == 5'>
				<h2>Please fill in your address?</h2>
				<textarea id='address' ng-model='details.address'></textarea>
				<button type='button' class='nextstep' placeholder='Your address...' ng-click='(details.address != "") ? increaseStep() : step=8; increaseStep();'><i class="fa fa-check"></i> Next (or skip)</button>
				<button type='button' class='previousstep' ng-click='decreaseStep()'><i class="fa fa-arrow-left"></i> Back</button>
			</div>

			<div data-step='6' ng-show='step == 6'>
				<h2>In what city is that?</h2>
				<input id='city' class='tac' ng-model='details.city' type='text'>
				<button type='button' class='nextstep' ng-click='increaseStep()'><i class="fa fa-check"></i> Next (or skip)</button>
				<button type='button' class='previousstep' ng-click='decreaseStep()'><i class="fa fa-arrow-left"></i> Back</button>
			</div>

			<div data-step='7' ng-show='step == 7'>
				<h2>In what region is that?</h2>
				<input id='region' class='tac' ng-model='details.region' type='text'>
				<button type='button' class='nextstep' ng-click='increaseStep()'><i class="fa fa-check"></i> Next (or skip)</button>
				<button type='button' class='previousstep' ng-click='decreaseStep()'><i class="fa fa-arrow-left"></i> Back</button>
			</div>

			<div data-step='8' ng-show='step == 8'>
				<h2>Great! And what's your postal code?</h2>
				<input id='postcode' class='tac' ng-model='details.postcode' type='text'>
				<button type='button' class='nextstep' ng-click='increaseStep()'><i class="fa fa-check"></i> Next (or skip)</button>
				<button type='button' class='previousstep' ng-click='decreaseStep()'><i class="fa fa-arrow-left"></i> Back</button>
			</div>

			<div data-step='9' ng-show='step == 9'>
				<h2>On what phone number can we contact you?</h2>
				<input id='phone' class='tac' ng-model='details.phone' type='text'>
				<button type='button' class='nextstep' ng-click='increaseStep()'><i class="fa fa-check"></i> Next (or skip)</button>
				<button type='button' class='previousstep' ng-click='(details.address != "") ? decreaseStep() : step=5'><i class="fa fa-arrow-left"></i> Back</button>
			</div>

			<div data-step='10' ng-show='step == 10'>
				<h2>And do you have a mobile number?</h2>
				<input id='mobile' class='tac' ng-model='details.mobile' type='text'>
				<button type='button' class='nextstep' ng-click='increaseStep()'><i class="fa fa-check"></i> Next (or skip)</button>
				<button type='button' class='previousstep' ng-click='decreaseStep()'><i class="fa fa-arrow-left"></i> Back</button>
			</div>

			<div data-step='11' ng-show='step == 11'>
				<h2>Perfect. And your email address?</h2>
				<input id='email' class='tac' ng-model='details.email' type='email'>
				<button type='button' class='nextstep' ng-click='increaseStep()'><i class="fa fa-check"></i> Next (or skip)</button>
				<button type='button' class='previousstep' ng-click='decreaseStep()'><i class="fa fa-arrow-left"></i> Back</button>
			</div>

			<div data-step='12' ng-show='step == 12'>
				<h2>Just out of cusiosity... Where did you hear about us?</h2>
				<select id='adtype'>
					<option value='Newspaper bro'>Newspaper</option>
				</select>
				<button type='button' class='nextstep' ng-click='enterSignaturePage()'><i class="fa fa-check"></i> Next (or skip)</button>
				<button type='button' class='previousstep' ng-click='decreaseStep()'><i class="fa fa-arrow-left"></i> Back</button>
			</div>

			<div data-step='13' ng-show='step == 13'>
				<h2>We're almost there!</h2>
				<div class="tahoma">
					<p>In order for us to start the work we need your approval for our terms and conditions. You can read them here:</p>
					<div style='background-color: #E0E0E0; border: 1px solid black; max-height: 400px; overflow-y: auto; padding: 5px;'>
						<div>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Eaque consequuntur reiciendis iure qui, sapiente omnis unde ex commodi id cum, libero et fuga incidunt voluptates, consectetur. Voluptates facere, debitis obcaecati.</div>
						<div>Optio aperiam id, cum, rem, accusantium provident excepturi eaque ea facilis, quis maxime in animi inventore ex unde. Dolorem minima, blanditiis beatae sint id totam itaque soluta laudantium animi tenetur!</div>
						<div>Aspernatur voluptates temporibus voluptatem vero asperiores! Iste natus voluptates ut iusto minus earum odio veritatis harum. Similique minima, et voluptate cupiditate facilis obcaecati praesentium in atque corporis labore! Iusto, ipsa.</div>
						<div>Mollitia, eum, architecto excepturi optio atque, illum dolorem obcaecati odit impedit sunt officiis eveniet. Aut sint consectetur unde vel labore, eos quae maxime adipisci, sapiente fugiat praesentium voluptatum commodi fugit.</div>
						<div>Dicta, iure magnam aliquid non! Odio officia dicta dignissimos minima deserunt ut optio voluptatem, est doloremque. Architecto quas quod eaque ad dicta voluptate provident, voluptatem iusto doloribus, esse voluptas, exercitationem!</div>
						<div>Quis quidem atque aut qui, cum assumenda, impedit sunt ab neque nemo ea distinctio nobis, voluptas animi reiciendis? Debitis earum cumque quo dolores numquam, repellendus animi quibusdam eaque soluta? Libero.</div>
						<div>Minima quae eum quaerat reprehenderit aut quam, aliquid maiores voluptatum. Quae veniam, ipsam! Dicta accusamus veniam culpa voluptatibus, commodi est modi, laudantium libero quidem laboriosam error, nihil quod, fuga quia?</div>
						<div>Quisquam ullam, aspernatur pariatur sit aut inventore nostrum nemo debitis error. Tempora voluptates dolore odit, voluptate iusto quisquam molestias voluptatibus rem odio hic laudantium ducimus ex corporis dolorum non tenetur.</div>
						<div>Nulla excepturi odit vel iste deleniti aut eligendi laudantium, odio harum dolorem magni recusandae animi. Nobis ducimus est modi, optio iure aliquam alias ipsam, aut explicabo provident labore autem non.</div>
						<div>Eius nobis quis, placeat maxime ad, necessitatibus dolor, sunt cupiditate quo minus numquam eveniet labore vel aliquam, provident quasi! Cumque, quo? Laboriosam nihil voluptatibus nam molestias commodi laborum voluptatem eos.</div>
						<div>Assumenda est magni saepe maxime, voluptas, ex ullam eum sunt adipisci! Ut, natus repellendus veniam sunt nam amet quia distinctio perspiciatis illum perferendis, maiores officiis accusamus itaque! Recusandae aspernatur, hic!</div>
						<div>Possimus corporis nostrum incidunt nobis sint quis eligendi aperiam ullam dolor repellat, aspernatur ratione impedit, expedita blanditiis, velit delectus omnis consequuntur! Fuga omnis ex nesciunt dolor harum laboriosam totam nisi?</div>
						<div>Tempora corporis officia sed, quaerat itaque natus, sint quibusdam nemo sapiente minus facere non. Ea exercitationem ipsum eaque explicabo, neque? Impedit quo porro id, non provident fugit accusantium nulla reprehenderit.</div>
						<div>Delectus ipsam obcaecati officia nesciunt, libero reiciendis modi, fugit debitis laboriosam explicabo, rerum quae optio. Omnis incidunt sint, quae commodi illo perspiciatis veniam, minima, quam consequuntur ratione debitis a vero!</div>
						<div>Repellat quis officiis modi expedita in quod ea commodi, aliquid incidunt repudiandae voluptatum odio dolor cumque error iste. Ratione dolore ullam fugiat ut dolores adipisci unde ex corrupti, deleniti expedita.</div>
						<div>Quasi nisi voluptate cum in omnis dignissimos soluta rerum ratione ipsa beatae nihil laborum cupiditate commodi non sequi vel magni a, aut reiciendis voluptates, odio ipsum atque! Id, enim facere.</div>
						<div>Veniam officiis libero voluptatum repellendus quod voluptas, recusandae in corporis quam vel, placeat aliquid odit, excepturi illum autem labore optio. Recusandae voluptatibus, hic odio eveniet commodi molestias! Animi, earum, adipisci!</div>
						<div>Veritatis eaque voluptate ipsum unde, voluptatibus consequuntur. Temporibus, tempora ipsam assumenda laborum possimus nihil expedita esse asperiores ea sed! Minus ad similique pariatur assumenda, atque adipisci alias excepturi impedit maiores.</div>
						<div>Nam quisquam porro accusantium quo, provident reprehenderit veniam dolores quae commodi id similique accusamus alias consectetur aperiam officia, consequatur molestias repellendus omnis suscipit inventore quas. Cum voluptas fuga accusantium nobis!</div>
						<div>Eius, ut molestiae. Tempore, obcaecati optio, facilis, accusantium eius est illo asperiores ducimus quos et reiciendis sunt! Fuga suscipit debitis necessitatibus accusantium id illum eum asperiores molestias totam. A, quibusdam?</div>
					</div>
					<br>
					<input type='hidden' name='acceptTandC' value='0'>
					<input type='checkbox' name='acceptTandC' value='1'> I, <% details.name %>, accept the terms and conditions. Date {{ date('d-m-Y') }}
					<br><br>
					Your signature:
					<div id='signature' width='400' height='300'></div>
					<button type='button' onclick='$("#signature").jSignature("reset");'>Reset signature</button>
					<br><br>

					<input type='hidden' name='newsletter' value='0'>
					<input type='checkbox' name='newsletter' value='1'>
					Yes, I would like to receive a monthly newsletter in my inbox!
					<br><br>
				</div>
				<button type='button'class='nextstep' ng-click='saveForm()'><i class="fa fa-check"></i> Go!</button>
				<button type='button' class='previousstep' ng-click='decreaseStep()'><i class="fa fa-arrow-left"></i> Back</button>
			</div>
		</div>
	</body>
</html>