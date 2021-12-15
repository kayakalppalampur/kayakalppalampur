@extends('layouts.front.front_layout')
@section('page_title') FX Alerts- Pricing Page @endsection
@section('title') FX Alerts @endsection
@section('content')
<div class="container">

    @php $featured_books = \App\CustomPopup::where('featured', 1)->orWhere('shared_ebook', 1)->get(); @endphp
    @if($featured_books->count() > 0)
        @foreach($featured_books as $book)
            <section class="ebook container" style="padding-top: 30px;">
                <div class="col-lg-10 col-lg-offset-1">
                    <!--<h1 class="text-center" style="padding: 30px 0;    letter-spacing: 0px;
              text-transform: none;
              font-size: 25px;
              color: #444444;
              text-align: center;
              font-style: inherit;
              font-weight: inherit;
              padding-top: 0px;
              padding-bottom: 20px;">WAIT! SHARE WITH A FRIEND AND GET ANOTHER GREAT BOOK</h1>-->


                    @if($loop->iteration%2 != 0)
                        <div class="popup-image col-lg-5">
                            <img src="{{ \App\SystemFile::getImageUrl($book, 'ebook_mockup') }}">
                        </div>
                        <div class="popup-content col-lg-7">
                            @if($loop->iteration == 1)
                                <h2>IF YOU SERIOUSLY WANT TO BROADEN YOUR KNOWLEDGE OF INVESTING AND TRADING, I RECOMMEND YOU DOWNLOAD THESE BOOKS NOW.</h2>
                            @endif
                            <h2 class="text-center" style="color: #d22f2f; font-weight: bold; font-size: 24px;">{{ $book->ebook_title }}</h2>
                            <h3>{{ $book->author }}</h3>


                            {!! $book->message !!}
                                <div class="clearfix"></div><br/>
                                <div class="form-popup">
                                <form action="{{ route('ebook.subscribe', ['ebook_id' => $book->id]) }}" method="POST">
                                    <input type="text" placeholder="Name" name="merge[fname]" required="">
                                    <input type="text" placeholder="Email" name="email" required="">

                                    <button type="submit" class="btn-popup">Download Now</button>

                                    <div class="clearfix"></div><br/>
                                    {{ csrf_field() }}
                                </form>
                                </div>
                        </div>

                    @else
                        <div class="popup-content col-lg-7">
                            @if($loop->iteration == 1)
                                <h2>IF YOU SERIOUSLY WANT TO BROADEN YOUR KNOWLEDGE OF INVESTING AND TRADING, I RECOMMEND YOU DOWNLOAD THESE BOOKS NOW.</h2>
                            @endif
                            <h2 class="text-center" style="color: #d22f2f; font-weight: bold; font-size: 24px;">{{ $book->ebook_title }}</h2>
                            <h3>{{ $book->author }}</h3>
                            {!! $book->message !!}
                                <div class="clearfix"></div><br/>
                                <div class="form-popup">
                                    <form action="{{ route('ebook.subscribe', ['ebook_id' => $book->id]) }}" method="POST">
                                        <input type="text" placeholder="Name" name="merge[fname]" required="">
                                        <input type="text" placeholder="Email" name="email" required="">

                                        <button type="submit" class="btn-popup">Download Now</button>

                                        <div class="clearfix"></div><br/>
                                        {{ csrf_field() }}
                                    </form>
                                </div>
                        </div>

                        <div class="popup-image col-lg-5">
                            <img src="{{ \App\SystemFile::getImageUrl($book, 'ebook_mockup') }}">
                        </div>

                    @endif

                </div>
        @endforeach
    @endif
    </section>
</div>
@endsection
