<div class="navbar navbar-inverse">
    <div class="navbar-header">
        <a class="navbar-brand" href="/trading">
            <img src="{{asset('trading-assets/images/bitfinex.svg')}}" alt="">
        </a>
        <ul class="nav navbar-nav pull-right visible-xs-block">
            <li>
                <a data-toggle="collapse" data-target="#navbar-mobile">
                    <i class="icon-tree5"></i>
                </a>
            </li>
        </ul>
    </div>
    <div class="navbar-collapse collapse" id="navbar-mobile">
        <ul class="nav navbar-nav">
            <li>
                <a href="/">
                    <i class="icon-arrow-left8 position-left"></i>
                    بازگشت به خانه
                </a>
            </li>
        </ul>

        <ul class="nav navbar-nav navbar-right">
            <li class="dropdown dropdown-user">
                <a href="#">
                    <img src="{{asset('trading-assets/images/man.png')}}" alt="">
                    <span>{{auth()->user()->email}}</span>
                </a>
            </li>
        </ul>
    </div>
</div>