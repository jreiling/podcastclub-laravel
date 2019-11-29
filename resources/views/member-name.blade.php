@extends('layouts.app')

@section('content')

      <header>
          PodcastClub
      </header>

      <div class="container-sm">

        <form method="post" action="/api/member-name">
          @csrf
          <div class="form-group">
            <label for="nameInput">Can I get your first name?</label>
            <input type="hidden" name="memberHash" value="{{ $hash }}">
            <input type="text" name="first_name" class="form-control" id="first_name">
          </div>

          <button type="submit" class="btn btn-primary">Yep, here it is!</button>
        </form>
      </div>

@endsection
