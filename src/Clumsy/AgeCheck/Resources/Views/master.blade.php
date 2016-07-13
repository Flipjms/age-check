@form(['url' => route('age-check.validateForm'), 'id' => 'form-agecheck'])
    @yield('day')
    @yield('month')
    @yield('year')

    @yield('country')

    @yield('remeber_me')

    @yield('submit')
@endform