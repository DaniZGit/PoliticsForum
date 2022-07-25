<!DOCTYPE html>
<html lang="en">
    @include('partials.header')
<body>
    <div id="page-container" class="position-relative">
        
        @include('partials.navigation')

        <section class="container-fluid row m-0 p-0" id="app">
            <router-view></router-view>
        </section>

        @include('partials.footer')

    </div>

    <script src="/js/user.js"></script>
</body>
</html>