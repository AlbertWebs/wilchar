@extends('front.master')

@php
    $metaTitle = $page->meta_title ?? $page->title;
    $metaDescription = $page->meta_description ?? $page->excerpt;
@endphp

@section('content')
    <section class="section" style="padding-top: 120px; padding-bottom: 80px;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-12 col-lg-10 col-xl-8">
                    <div class="page-content">
                        <h1 class="page-title mb-4 wow fadeInUp" data-wow-duration="0.8s">{{ $page->title }}</h1>
                        
                        @if($page->excerpt)
                        <p class="page-excerpt text-muted mb-4 wow fadeInDown" data-wow-duration="0.8s">{{ $page->excerpt }}</p>
                        @endif

                        <div class="page-body wow fadeInUp" data-wow-duration="0.8s">
                            {!! $page->content !!}
                        </div>

                        @if($page->updated_at)
                        <div class="page-footer mt-5 pt-4 border-top">
                            <p class="text-muted small mb-0">
                                <i class="bi bi-clock me-1"></i>
                                Last updated: {{ $page->updated_at->format('F d, Y') }}
                            </p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <style>
        .page-content {
            background: #ffffff;
            border-radius: 16px;
            padding: 3rem;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .page-title {
            font-size: 2.5rem;
            font-weight: 700;
            color: #1e293b;
            line-height: 1.2;
        }

        .page-excerpt {
            font-size: 1.125rem;
            line-height: 1.6;
        }

        .page-body {
            font-size: 1rem;
            line-height: 1.8;
            color: #475569;
        }

        .page-body h2 {
            font-size: 1.75rem;
            font-weight: 700;
            color: #1e293b;
            margin-top: 2rem;
            margin-bottom: 1rem;
        }

        .page-body h3 {
            font-size: 1.5rem;
            font-weight: 600;
            color: #334155;
            margin-top: 1.5rem;
            margin-bottom: 0.75rem;
        }

        .page-body p {
            margin-bottom: 1rem;
        }

        .page-body ul,
        .page-body ol {
            margin-bottom: 1rem;
            padding-left: 2rem;
        }

        .page-body li {
            margin-bottom: 0.5rem;
        }

        .page-body a {
            color: #ed741b;
            text-decoration: none;
            transition: color 0.2s ease;
        }

        .page-body a:hover {
            color: #cc231b;
            text-decoration: underline;
        }

        @media (max-width: 767.98px) {
            .page-content {
                padding: 2rem 1.5rem;
            }

            .page-title {
                font-size: 2rem;
            }
        }
    </style>
@endsection
