<h2 class="question" id="comment-{{ $question->number }}">
    <a href="{{ $question->link }}" class="reference" target="_blank">#{{ $question->number }}</a>
    <small>{{ $question->user }}</small>

    {!! $question->text_html !!}
</h2>

<div class="response" id="comment-{{ $response->number }}">
    {!! $response->text_html !!}
</div>

<hr />