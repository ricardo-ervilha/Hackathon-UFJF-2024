<form action="" method="">
    @foreach ($collection as $key => $item)
        <p>{{ $key }}, {{ $item }} </p>
    @endforeach
</form>