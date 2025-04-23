<section class="my-5">
    <h2>{{ $title }}</h2>

    <div class="arrows-around position-relative mt-4">
        <div class="arrows">
            <button class="btn custom-button arrow-left" id="{{ $id_arrow_left }}">
                <span class="material-icons">chevron_left</span>
            </button>
            <button class="btn custom-button arrow-right" id="{{ $id_arrow_right }}">
                <span class="material-icons">chevron_right</span>
            </button>
        </div>

        <div class="articles-carousel-container">
            <div class="articles-row" id="{{ $id }}">

                @foreach ($articles ?? [] as $article)
                    <div class="col">
                        <div class="card text-start p-3">
                            <img src="{{ asset($article['image']) }}" alt="{{ $article['title'] }}" class="img-half-cover">

                            <div class="card-body">
                                <a href="#" class="text-decoration-none">
                                    <h5 class="card-title truncate-ellipsis">{{ $article['title'] }}</h5>
                                </a>

                                <div class="d-flex align-items-center gap-2">
                                    <a href="#" class="d-flex align-items-center truncate-ellipsis gap-2 text-decoration-none">
                                        <span class="material-icons">account_circle</span>
                                        <span class="text-muted">{{ $article['author'] }}</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach

            </div>
        </div>
    </div>
</section>