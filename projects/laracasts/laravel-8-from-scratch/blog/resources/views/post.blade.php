<x-layout>
    <article>
        <h1>
            {{ $post->title }}
        </h1>

        <p>
            <a href="#">
                {{ $post->category->name }}
            </a>
        </p>

        {!! $post->body !!}
    </article>

    <a href="/">Go Back</a>
</x-layout>