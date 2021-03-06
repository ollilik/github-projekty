@extends('layouts.website')

@section('content')

<div id="container">
    @if(Session::has('custom_message'))
      <div class="alert alert-success" role="alert">
          {{ Session::get('custom_message') }}
      </div>
    @endif
    <!-- Stack the columns on mobile by making one full-width and the other half-width -->
    <div class="row">
        <h3>{{$auction->title}}</h3>
    </div>
    <div class="row">
        <div class="col-12 col-md-6">
            <div id="carouselExampleIndicators" class="carousel slide" data-ride="carousel">
              <!--
                <ol class="carousel-indicators">
                  <li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>
                  <li data-target="#carouselExampleIndicators" data-slide-to="1"></li>
                  <li data-target="#carouselExampleIndicators" data-slide-to="2"></li>
                </ol>
              -->
                <div class="carousel-inner">
                  @if(!$auction->pictures->isEmpty())
                  @foreach($auction->pictures as $key => $picture)
                  <div class="carousel-item @if($key == 0) active @endif">
                    <img class="d-block w-100" src="{{ asset('storage/auctions/' . $auction->id . '/' . $picture->file_name) }}">
                  </div>
                  @endforeach
                  @else 
                  <div class="carousel-item active">
                    <img class="card-img-top" src="{{ asset('storage/auctions/default.png') }}" alt="Thumbnail">
                  </div>
                  @endif
                </div>
                <a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
                  <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                  <span class="sr-only">Previous</span>
                </a>
                <a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
                  <span class="carousel-control-next-icon" aria-hidden="true"></span>
                  <span class="sr-only">Next</span>
                </a>
              </div>
        </div>
        <div class="col-12 col-md-6">
            <div class="row">
                <div class="col-md-7">
                    @if($auction->type == "popt??vkov??")
                    <p>@if( $auction->rule == "uzav??en??") Nejni?????? mo??n?? nab??dka @else Aktualn?? cena @endif</p>
                    <h2>@if( $auction->rule == "uzav??en??") {{$auction->min_cost }} @else {{$auction->top_bid() }} @endif K??</h2>
                    <p>Okam??it?? n??kup: {{ $auction->max_cost }} k??</p>
                    @else 
                    <p>@if( $auction->rule == "uzav??en??") Maxim??ln?? mo??n?? nab??dka @else Aktualn?? cena @endif</p>
                    <h2>@if( $auction->rule == "uzav??en??") {{$auction->max_cost }} @else {{$auction->lowest_bid() }} @endif K??</h2>
                    <p>Okam??it?? prodej: {{ $auction->min_cost }} k??</p>
                    @endif
                    <p>{{ $auction->rule }}, {{$auction->type}} aukce</p>
                    @if($auction->auctioneer_id)
                    <p>Start: {{$auction->start_at->format('Y.m.d H:i:s')}}</p>
                    <p>Konec: {{$auction->end_at->format('Y.m.d H:i:s')}}</p>
                    @endif
                </div>
                <div class="col-md-5">
                    @if($auction->running())
                    <form action="/offers/{{$auction->id}}/bid" method="post">
                    @csrf <!-- {{ csrf_field() }} -->
                        <div class="form-group">
                            <small id="bidHelp" class="form-text text-muted">Kolik chcete za zbo???? d??t?</small>
                            
                            <input type="number" class="form-control" id="bid" name="bid" value="0">
                        </div>
                        <button type="submit" class="btn btn-outline-success my-2 my-sm-0">P??ihodit</button>
                    </form>
                    @endif
                    @auth
                      @if($auction->auctioneer_id)
                        @if(Auth::user()->id == $auction->auctioneer_id )
                          <a href="/auction/{{$auction->id}}/signed-users" class="btn btn-primary">Zobrazit registrace</a>
                        @elseif(Auth::user()->id != $auction->author_id)
                          @if(!$auction->users->contains(Auth::user()))
                          <a href="/auction/{{$auction->id}}/sign-up" class="btn btn-primary">P??ihl??sit se do aukce</a>
                          @else
                          <b>{{ $auction->users()->where('user_id', '=', Auth::user()->id)->first()->pivot->status }}</b>
                          @endif
                        @endif
                      @else
                        @if($auction->author->id == Auth::user()->id)
                          <a href="/auctions/{{$auction->id}}/edit" class="btn btn-primary">Upravit</a>
                        @endif
                        @canany(['auctioneer', 'admin'])
                          <a href="/auctions/{{$auction->id}}/confirm" class="btn btn-primary">Schv??lit</a>
                        @endcanany
                      @endif
                    @endauth
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <h4>Autor: {{ $auction->author->name }}</h4>
                    <!--
                    <a href="#" class="btn btn-primary">Nab??dky prodejce v t??to kategorii</a>
                    <a href="#" class="btn btn-primary">V??echny nab??dky prodejce</a>
                    -->
                </div>
                <div class="col-md-6">
                    <h4>Organizator: @if($auction->auctioneer) {{ $auction->auctioneer->name }}@endif</h4>
                    <!--
                    <a href="#" class="btn btn-primary">Nab??dky organiz??tora v t??to kategorii</a>
                    <a href="#" class="btn btn-primary">V??echny nab??dky organiz??tora</a>
                    -->
                </div>
            </div>
            <div class="row">
              <div class="col-12">
                {{ $auction->description }}
              </div>
            </div>
        </div>
    </div>
    {{-- {{$auction}} --}}
    <br>
    {{-- TODO: Tady bych p??idal aukce ze stejne kategorie --}}
</div>
@endsection