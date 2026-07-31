@extends('layouts.app')

@section('title', 'Danh mục Homestay | HomeStayGo')

@section('content')

    <x-frontend-breadcrumb :items="[
        [
            'label' => 'Trang chủ',
            'url' => route('home'),
        ],
        [
            'label' => 'Danh mục',
        ],
    ]" />

    <section class="border-b border-slate-200 bg-white">
        <div class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
            <p class="font-semibold uppercase tracking-widest text-blue-600">
                Loại hình lưu trú
            </p>

            <h1 class="mt-2 text-3xl font-bold text-slate-950 sm:text-4xl">
                Danh mục Homestay
            </h1>

            <p class="mt-2 max-w-2xl text-slate-500">
                Khám phá Homestay theo từng loại hình phù hợp với nhu cầu của bạn.
            </p>
        </div>
    </section>

    <main class="mx-auto max-w-7xl px-4 py-10 sm:px-6 lg:px-8">
        @if ($categories->isEmpty())
            <div
                class="rounded-2xl border border-dashed border-slate-300 bg-white px-6 py-16 text-center"
            >
                <h2 class="text-xl font-bold text-slate-900">
                    Chưa có danh mục
                </h2>

                <p class="mt-2 text-sm text-slate-500">
                    Danh mục Homestay đang được cập nhật.
                </p>
            </div>
        @else
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                @foreach ($categories as $category)
                    <a
                        href="{{ route('categories.show', $category->slug) }}"
                        class="group rounded-2xl border border-slate-200 bg-white p-6 shadow-sm transition hover:-translate-y-1 hover:border-blue-200 hover:shadow-lg"
                    >
                        <div class="flex items-start justify-between gap-4">
                            <div>
                                <h2
                                    class="text-xl font-bold text-slate-900 transition group-hover:text-blue-600"
                                >
                                    {{ $category->name }}
                                </h2>

                                <p class="mt-2 line-clamp-2 text-sm leading-6 text-slate-500">
                                    {{ $category->description ?: 'Khám phá các Homestay thuộc danh mục này.' }}
                                </p>
                            </div>

                            <span
                                class="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-blue-50 text-blue-600 transition group-hover:bg-blue-600 group-hover:text-white"
                            >
                                →
                            </span>
                        </div>

                        <div class="mt-5 border-t border-slate-100 pt-4">
                            <span class="text-sm font-semibold text-blue-600">
                                {{ $category->homestays_count }} Homestay
                            </span>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </main>

@endsection