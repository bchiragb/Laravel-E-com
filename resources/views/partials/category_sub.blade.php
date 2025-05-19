@php $depth++; @endphp
@foreach ($categories as $subCategory)
    @if ($subCategory->parent === $parentId)
        @php
            $childCategories = $categories->where('parent', $subCategory->id);
        @endphp
        <li class="level{{ $depth }} @if ($childCategories->isNotEmpty()) sub-level @endif">
            <a href="{{ $childCategories->isNotEmpty() ? 'javascript:void(0);' : $domain.'/category/'.$subCategory->slug }}" class="site-nav opener">{{ $subCategory->name }}</a>
            @if ($childCategories->isNotEmpty())
                <ul class="sublinks">
                    <li class="level{{ $depth+1 }} viewall"><a href="{{ $domain.'/category/'.$subCategory->slug }}" class="site-nav">View All {{ $subCategory->name }}</a></li>
                    @include('partials.category_sub', ['categories' => $categories, 'parentId' => $subCategory->id, 'level' => $depth])
                </ul>
            @endif
        </li>
    @endif
@endforeach
