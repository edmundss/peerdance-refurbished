@if (Session::has('message'))
  <script type="text/javascript">
    alertify.success("{{{Session::get('message')}}}");
  </script>
@endif

@if ($errors->all())
    @foreach ($errors->getMessages() as $value)
      <script type="text/javascript">
       alertify.error("{{$value[0]}}");
      </script>
    @endforeach
@endif
<script>
    $(function(){

            $('.default-avatar-top').materialAvatar({
              shape: 'circle'
            });
    })
</script>
<script src="{!! asset('plugins/intro.js/intro.js') !!}" charset="utf-8"></script>
@yield('scripts')
@yield('forum_scripts')
@yield('modals')
