@extends('layouts.app')

@section('content')

      <header>
          {{ $first_name }}'s Podcast Queue
      </header>

      <div class="container">

        <member></member>

        <hr />
        <p>To add a new podcast, just text the link to <a href="sms:podcastclub@icloud.com">podcastclub@icloud.com</a>. If you belong to multiple clubs, the podcast you send will be added to the queue for all clubs.</p>
        <p>New podcasts are sent every Monday and Thursday.</p>
        <p>Make your experience extra 🤖 with the <a href="/images/PodcastClub.vcf">PodcastClub vCard</a>.</p>

      </div>

@endsection
