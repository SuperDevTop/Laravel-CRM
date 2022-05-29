<?php
	use App\Models\Quote;
	use App\Models\User;
	use App\Models\Changelog;
	use App\Models\Settings;
	use App\Models\invoices\Invoice;
 ?>

<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<base href="{{ Request::root() }}">
	<title>{{ Settings::setting('app_name') }} - @yield('page_name')</title>
	<link rel="shortcut icon" type="/image/png" href="/icon.png"/>
	<link rel="stylesheet" href="/css/normalize.css">
	<link rel="stylesheet" href="/css/layout.css">
	<link rel="stylesheet" href="/css/ui_elements.css">
	<link rel="stylesheet" href="/css/grid.css">
	<link rel="stylesheet" href="/css/select2.css">
	<link rel="stylesheet" href="/css/jquery-ui.min.css">
	<link rel="stylesheet" href="/css/search_anything.css">
	<link rel="stylesheet" href="/css/chat.css">
	
	<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" href='//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700,800'>

	@yield('stylesheets')

	<script type="text/javascript" src="//code.jquery.com/jquery-1.11.2.js"></script>
	<script type="text/javascript" src='https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/js/select2.full.js'></script>
	<script type="text/javascript" src='/js/tablesorter.js'></script>
	<script type="text/javascript" src='/js/jquery-ui.min.js'></script>
	<script type="text/javascript" src='/js/jquery.timepicker.min.js'></script>
	<script type="text/javascript" src='/js/howler.min.js'></script>
	<script type="text/javascript" src='/js/autosize.min.js'></script>
	<script type="text/javascript" src='/js/datefunctions.js'></script>
	<script type="text/javascript" src='/js/angularjs.min.js'></script>

	<script type="text/javascript" src="/js/main.js"></script>
	<script type="text/javascript" src='/js/tabs.js'></script>
	<script type="text/javascript" src="/js/basinput.js"></script>
	<script type="text/javascript" src="/js/search_anything.js"></script>
	
	@if(Settings::setting('chatEnabled'))
		
	@endif
	
</head>

<ul id="nav_menu">
	<li>
		<a href="dashboard"><i class="fa fa-dashboard"></i> Dashboard</a>
	</li>
	@if($user->hasPermission('manage_users') || $user->hasPermission('system_settings') || $user->hasPermission('template_settings') || $user->hasPermission('manage_system_variables'))
	<li>
		<a href="#" class="has_sub"><i class="fa fa-gear"></i> System</a>
		<ul class="nav_submenu">
			@if ($user->hasPermission('edit_settings')) <li><a href="{{ url('settings');}}">System settings</a></li> @endif
			@if ($user->hasPermission('manage_users')) <li><a href={{ url('users');}}>Manage users</a></li> @endif
			@if ($user->hasPermission('manage_user_groups')) <li><a href={{ url('user-groups');}}>Manage User Groups</a></li> @endif
			@if ($user->hasPermission('manage_system_variables')) <li><a href={{ url('variables');}}>Manage System Variables</a></li> @endif
		</ul>
	</li>
	@endif
	@if($user->hasPermission('product_create') || $user->hasPermission('product_edit') || $user->hasPermission('product_delete'))
		<li>
			<a href="products"><i class="fa fa-shopping-cart"></i> Products</a>
		</li>
	@endif
	@if($user->hasPermission('customer_view') || $user->hasPermission('customer_create') || $user->hasPermission('customer_edit') || $user->hasPermission('customer_delete'))
		<li>
			<a href="#" class="has_sub"><i class="fa fa-user"></i> Customers</a>
			<ul class="nav_submenu">
				@if ($user->hasPermission('customer_create')) <li><a href={{ url('customers/create');}}>New Customer</a></li> @endif
				@if ($user->hasPermission('customer_view')) <li><a href={{ url('customers'); }}>List Customers</a></li> @endif
			</ul>
		</li>
	@endif
	@if($user->hasPermission('quotes_view'))
		<li>
			<a href="#" class="has_sub"><i class="fa fa-quote-right"></i> Quotes & invoices</a>
			<ul class="nav_submenu">
				<li><a href= {{url('quotes/' . Quote::max('id') . '/edit');}}> Open last quote</a></li>
				<li><a href={{ url('invoices/' . Invoice::max('id') . '/edit'); }}>Open last invoice</a></li>
				<li><a href={{ url('quotes'); }}>List quotes</a></li>
				<li><a href={{ url('invoices'); }}>List invoices</a></li>
				<li><a href={{ url('myjobs'); }}>My jobs</a></li>
			</ul>
		</li>
	@endif
	@if($user->hasPermission('supplier_create') || $user->hasPermission('supplier_edit') || $user->hasPermission('supplier_delete'))
		<li>
			<a href="suppliers"><i class="fa fa-truck"></i> Suppliers</a>
		</li>
	@endif
	@if($user->hasPermission('direct_debit_create') || $user->hasPermission('direct_debit_list'))
		<li>
			<a href="#" class='has_sub'><i class="fa fa-files-o"></i> Direct Debiting</a>
			<ul class="nav_submenu">
				@if ($user->hasPermission('direct_debit_create')) <li><a href={{ url('ddNewJob'); }}>New Direct Debit Job</a></li> @endif
				@if ($user->hasPermission('direct_debit_list')) <li><a href={{ url('ddIndex'); }}>List jobs</a></li> @endif
			</ul>
		</li>
	@endif
	@if($user->hasPermission('payment_create') || $user->hasPermission('payment_list'))
		<li>
			<a href="#" class='has_sub'><i class="fa fa-dollar"></i> Payments</a>
			<ul class="nav_submenu">
				@if ($user->hasPermission('payment_create')) <li><a href={{ url('payments/create'); }}>New payment</a></li> @endif
				@if ($user->hasPermission('payment_list')) <li><a href={{ url('payments'); }}>List payments</a></li> @endif
			</ul>
		</li>
	@endif

	{{-- TODO ADD PERMISSIONS --}}
	<li>
		<a href="#" class='has_sub'><i class="fa fa-beer"></i> Expenses</a>
		<ul class="nav_submenu">
			<li><a href={{ url('expenses'); }}>List expenses</a></li>
			<li><a href={{ url('expenses/create') }}>Create expense</a></li>
			<li><a href={{ url('expenses/categories') }}>Manage categories</a></li>
		</ul>
	</li>
	
	@if($user->hasPermission('renewal_list_due'))
		<li>
			<a href="renewals"><i class="fa fa-refresh"></i> Renewals</a>
		</li>
	@endif
	
	@if ($user->hasPermission('reports_visual') || $user->hasPermission('reports_listed'))
		<li>
			<a href="#" class='has_sub'><i class="fa fa-bar-chart"></i> Reports</a>
			<ul class="nav_submenu">
				@if ($user->hasPermission('reports_visual')) <li><a href={{ url('reports/overview'); }}>Overview</a></li> @endif
				@if ($user->hasPermission('reports_listed')) <li><a href={{ url('reports/lists'); }}>List reports</a></li> @endif
				@if ($user->hasPermission('reports_visual')) <li><a href={{ url('reports/charts'); }}>Visual reports</a></li> @endif
			</ul>
		</li>
	@endif
	
	<li>
		<a href="#" id='nav_open_media_gallery'><i class="fa fa-picture-o"></i> Media Gallery</a>
	</li>

	@if (Settings::setting('chatEnabled'))
		<li>
			<a href="#" class='has_sub'><i class="fa fa-comments"></i> Chat</a>
			<ul class="nav_submenu" id='chat_navigation_ul'>
				<li class='chat_list_new'>
					<a href="#" onclick='return false;' id='newchat'>
						<i class="fa fa-search" style='color: #17BFFF;'></i> <input type="text" placeholder='Offline users'>
					</a>
				</li>
				@foreach(User::getActiveUsers() as $user)
					@if($user->id != Auth::id())
						<li class='chat_list_user offline' data-user-id='{{ $user->id }}'><a href="#" onclick='return false;'>{{ $user->getFullname() }}</a></li>
					@endif
				@endforeach
			</ul>
		</li>
	@endif
</ul>