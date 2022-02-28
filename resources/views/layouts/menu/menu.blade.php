<header>
	<!-- This is a container for navbar-->
	<div class="header">

		<!-- Site logo-->
		<a href="START">
			<div class="logo">
				<img src={{ asset('img/logo-square.png') }}>
			</div>
		</a>

		<!-- Menu button for mobile version-->
		<input id="menu-toggle" type="checkbox" />
		<label class='menu-button-container' for="menu-toggle">
				<div class='menu-button'></div>
		</label>

		<!-- Navigation list (subpages)-->
        {!! \App\Services\MenuHelper::ol($menuItems, Request::path()) !!}

    @section('social_bar')

    @show
    @section('contact_bar')

    @show

	</div>
    @if (!Request::is('*/'))
    <div class="title">
        <h1>@yield('title')</h1>
    </div>
    @endif
</header>
